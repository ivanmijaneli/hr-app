<?php

namespace Tests\Unit;

use App\Models\Document;
use App\Models\Employee;
use App\Models\JobDescription;
use App\Models\JobStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class EmployeeTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_has_path(): void
    {
        $employee = Employee::factory()->create();

        $this->assertEquals('/employees/' . $employee->id, $employee->path());
    }

    public function test_it_has_many_job_statuses(): void
    {
        $employee = Employee::factory()->create();
        JobStatus::factory()->create(['employee_id' => $employee->id]);

        $this->assertInstanceOf(JobStatus::class, $employee->jobStatusHistory[0]);
        $this->assertInstanceOf(JobStatus::class, $employee->jobStatus());
    }

    public function test_it_can_add_job_status()
    {
        $employee = Employee::factory()->create();

        $statusOne = $employee->addJobStatus(JobStatus::factory()->raw());
        $this->assertEquals($statusOne->id, $employee->jobStatus()->id);

        $statusTwo = $employee->addJobStatus(JobStatus::factory()->raw());
        $this->assertEquals($statusTwo->id, $employee->jobStatus()->id);
    }

    public function test_it_has_many_job_descriptions(): void
    {
        $employee = Employee::factory()->create();
        JobDescription::factory()->create(['employee_id' => $employee->id]);

        $this->assertInstanceOf(JobDescription::class, $employee->jobDescriptionHistory[0]);
        $this->assertInstanceOf(JobDescription::class, $employee->jobDescription());
    }

    public function test_it_can_add_job_description()
    {
        $employee = Employee::factory()->create();

        $descOne = $employee->addJobDescription(JobDescription::factory()->raw());
        $this->assertEquals($descOne->id, $employee->jobDescription()->id);

        $descTwo = $employee->addJobDescription(JobDescription::factory()->raw());
        $this->assertEquals($descTwo->id, $employee->jobDescription()->id);
    }

    public function test_it_has_many_documents()
    {
        $employee = Employee::factory()->create();
        Document::factory()->create(['employee_id' => $employee->id]);

        $this->assertInstanceOf(Document::class, $employee->documents[0]);
    }

    public function test_it_can_add_documents()
    {
        $employee = Employee::factory()->create();

        $docAttributes = Document::factory()->raw();
        $docAttributes['file'] = UploadedFile::fake()->create('document.pdf');

        $employee->addDocument($docAttributes);

        $this->assertNotNull($employee->documents[0]);
    }
}
