<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreJobPostRequest;
use App\Http\Requests\UpdateJobPostRequest;
use App\Models\Application;
use App\Models\JobPost;
use App\Models\Skill;
use App\Models\Industry;


class JobPostController extends Controller
{
  public function pendingPosts()
  {
    $jobPosts = JobPost::where('status', 'pending')->with('employer')->get()->each(function ($jobPost) {
      $jobPost->makeHidden(['user_id']);
    });
    return response()->json($jobPosts);
  }

  public function rejectedPosts()
  {
    $jobPosts = JobPost::where('status', 'rejected')->with('employer')->get()->each(function ($jobPost) {
      $jobPost->makeHidden(['user_id']);
    });

    return response()->json($jobPosts);
  }

  public function index()
  {
    $jobPosts = JobPost::where('status', 'approved')->get();
    for ($i = 0; $i < count($jobPosts); $i++) {
      $jobPosts[$i]->applicants_count = $jobPosts[$i]->appliedUsers()->count();
      $jobPosts[$i]->skills = join(',', $jobPosts[$i]->skills->pluck('id', 'name')->toArray());
      $jobPosts[$i]->industries = join(',', $jobPosts[$i]->industries->pluck('id', 'name')->toArray());
    }
    return response()->json($jobPosts);
  }

  public function store(StoreJobPostRequest $request)
  {
    $validatedData = $request->validated();
    $validatedData['status'] = 'pending';
    $requestSkills = explode(',', $request->skills);
    $requestIndustries = explode(',', $request->industries);
    $skillIds = [];
    $industryIds = [];

    foreach ($requestSkills as $skillName) {
      $skill = Skill::where('name', strtolower($skillName))->first();
      if (!$skill) {
        $skill = Skill::create(['name' => strtolower($skillName)]);
      }
      $skillIds[] = $skill->id;
    }

    foreach ($requestIndustries as $industryName) {
      $industry = Industry::where('name', strtolower($industryName))->first();
      if (!$industry) {
        $industry = Industry::create(['name' => strtolower($industryName)]);
      }
      $industryIds[] = $industry->id;
    }

    $jobPost = $request->user()->postedJobs()->create($validatedData);
    $jobPost->skills()->attach($skillIds);
    $jobPost->industries()->attach($industryIds);
    return response()->json($jobPost, 201);
  }


  public function show($id)
  {
    $jobPost = JobPost::findOrFail($id);
    $numberOfApplications = $jobPost->appliedUsers()->count();
    $jobPost->applicants_count = $numberOfApplications;
    $jobPost->skills = join(',', $jobPost->skills->pluck('id', 'name')->toArray());
    $jobPost->industries = join(',', $jobPost->industries->pluck('id', 'name')->toArray());
    return response()->json($jobPost);
  }

  public function update(UpdateJobPostRequest $request, $id)
  {
    $jobPost = JobPost::findOrFail($id);
    $validatedData = $request->validated();
    $jobPost->update($validatedData);

    $requestSkills = explode(',', $request->skills);
    $requestIndustries = explode(',', $request->industries);
    $skillIds = [];
    $industryIds = [];

    foreach ($requestSkills as $skillName) {
      $skill = Skill::where('name', strtolower($skillName))->first();
      if (!$skill) {
        $skill = Skill::create(['name' => strtolower($skillName)]);
      }
      $skillIds[] = $skill->id;
    }

    foreach ($requestIndustries as $industryName) {
      $industry = Industry::where('name', strtolower($industryName))->first();
      if (!$industry) {
        $industry = Industry::create(['name' => strtolower($industryName)]);
      }
      $industryIds[] = $industry->id;
    }

    $jobPost->skills()->sync($skillIds);
    $jobPost->industries()->sync($industryIds);
    return response()->json($jobPost);
  }

  public function approve(Request $request, $id)
  {
    $jobPost = JobPost::findOrFail($id);
    $jobPost->update(['status' => 'approved']);
    return response()->json($jobPost);
  }

  public function reject(Request $request, $id)
  {
    $jobPost = JobPost::findOrFail($id);
    $jobPost->update(['status' => 'rejected']);
    return response()->json($jobPost);
  }

  public function pend(Request $request, $id)
  {
    $jobPost = JobPost::findOrFail($id);
    $jobPost->update(['status' => 'pending']);
    return response()->json($jobPost);
  }
  public function destroy(Request $request, $id)
  {
    $jobPost = JobPost::findOrFail($id);
    $jobPost->delete();
    return response()->json(['message' => 'Job post deleted successfully']);
  }

  public function search(Request $request)
  {
    $keywords = $request->get('keywords');
    $city = $request->get('city');
    $country = $request->get('country');
    $type = $request->get('type');
    $remote_type = $request->get('remote_type');
    $experienceLevel = $request->get('experience_level');
    $minSalary = $request->get('min_salary');
    $maxSalary = $request->get('max_salary');
    $postedAfter = $request->get('posted_after');
    $skills = $request->get('skills');
    $industries = $request->get('industries');

    $jobs = $this->buildSearchQuery($keywords, $city, $country, $type, $remote_type, $experienceLevel, $minSalary, $maxSalary, $postedAfter, $skills, $industries);

    return response()->json($jobs);
  }


  private function buildSearchQuery($keywords, $city, $country, $type, $remote_type, $experienceLevel, $minSalary, $maxSalary, $postedAfter, $skills, $industries)
  {
    $query = JobPost::query();


    if ($keywords) {
      $keywords = explode(' ', $keywords);
      $query->where(function ($query) use ($keywords) {
        foreach ($keywords as $keyword) {
          $query->orWhere(function ($subquery) use ($keyword) {
            $subquery->where('title', 'LIKE', "%{$keyword}%")
              ->orWhere('description', 'LIKE', "%{$keyword}%");
          });
        }
      });
    }

    if ($city) {
      $query->where('city', 'LIKE', "%{$city}%");
    }

    if ($country) {
      $query->where('country', 'LIKE', "%{$country}%");
    }


    if ($remote_type) {
      $query->where('remote_type', $remote_type);
    }

    if ($type) {
      $query->where('type', $type);
    }


    if ($experienceLevel) {
      $query->where('experience_level', $experienceLevel);
    }


    if ($minSalary) {
      $minSalary = intval($minSalary);
      $query->where('min_salary', '>=', $minSalary);
    }

    if ($maxSalary) {
      $maxSalary = intval($maxSalary);
      $query->where('max_salary', '<=', $maxSalary);
    }


    if ($postedAfter) {
      $query->whereDate('created_at', '>=', $postedAfter);
    }

    if ($skills) {
      $query->with('skills');
      $requiredSkills = explode(' ', $skills);
      $query->whereHas('skills', function ($query) use ($requiredSkills) {
        $query->whereIn('name', $requiredSkills);
      });
    }

    if ($industries) {
      $query->with('industries');
      $Industries = explode(' ', $industries);
      $query->whereHas('industries', function ($query) use ($Industries) {
        $query->whereIn('name', $Industries);
      });
    }

    if (config('app.debug')) {
      \Log::debug('Search Query: ' . $query->toSql());
    }

    return $query->where('status', 'approved')->get();
  }


  public function jobPostApplications(Request $request, $id)
  {
    $jobPost = JobPost::findOrFail($id);

    $applications = $jobPost->appliedUsers()->withPivot('resume_path', 'status', 'id')->get();

    $applications = $applications->map(function ($application) {
      return [
        'id' => $application->pivot->id,
        'resume_path' => $application->pivot->resume_path,
        'status' => $application->pivot->status,
        'applied_at' => $application->pivot->created_at,
        'user' => [
          'id' => $application->id,
          'name' => $application->name,
          'email' => $application->email,
          'phone_number' => $application->phone_number,
          'profile_image' => $application->profile_image
        ],
      ];
    });

    return response()->json($applications);
  }

  public function employerJobPosts(Request $request)
  {
    $jobPosts = JobPost::where('user_id', $request->user()->id)
      ->get()->each(function ($jobPost) {
        $jobPost->applicants_count = $jobPost->appliedUsers()->count();
      });
    return response()->json($jobPosts);
  }

  public function filterParams()
  {
    $cities = JobPost::distinct()->pluck('city');
    $countries = JobPost::distinct()->pluck('country');
    $skills = Skill::pluck('name')->toArray();
    $industries = Industry::pluck('name')->toArray();
    return response()->json([
      'cities' => $cities,
      'countries' => $countries,
      'skills' => $skills,
      'industries' => $industries
    ]);
  }
}
