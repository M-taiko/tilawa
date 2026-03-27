<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tenant extends Model
{
    use SoftDeletes;
    protected $fillable = ['name', 'is_active', 'max_teachers', 'max_students'];

    public function users()
    {
        return $this->belongsToMany(User::class)
            ->withPivot(['role'])
            ->withTimestamps();
    }

    public function studyClasses()
    {
        return $this->hasMany(StudyClass::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function sessions()
    {
        return $this->hasMany(Session::class);
    }

    public function foundationSkills()
    {
        return $this->hasMany(FoundationSkill::class);
    }

    public function hasReachedTeacherLimit(): bool
    {
        return $this->users()->wherePivot('role', 'teacher')->count() >= $this->max_teachers;
    }

    public function hasReachedStudentLimit(): bool
    {
        return $this->students()->where('status', '!=', 'graduated')->count() >= $this->max_students;
    }
}
