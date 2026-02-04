<?php

namespace App\Services;

class ResumeFormDataBuilder
{
    public static function formContents(array $contents)
    {
        $workExps = [];
        $schoolBgItems = [];

        foreach($contents['work_exp'] as $company)
        {
            $workExps[] = [
                'job_company' => $company['job_company'],
                'job_title' => $company['job_title'],
                'job_period' => $company['job_period'],
                'job_desc' =>$company['job_desc']
            ];
        }

        foreach($contents['school_bg'] as $school)
        {
            $schoolBgItems[] = [
                'degree' => $school['bach_degr'],
                'institution' => $school['insti'],
                'school_year' => $school['sch_year'],
                'awards' => $school['sch_award']
            ];
        }
        return [
            'appli_name' => $contents['appli_firstName'].' '.$contents['appli_lastName'],
            'appli_posi' => $contents['appli_posi'],
            'appli_addre' => $contents['appli_addre'],
            'appli_email' => $contents['appli_email'],
            'contct_num' => $contents['contct_num'],
            'appli_fb' => $contents['appli_fb'],
            'skills' => $contents['appli_skills'],
            'work_items' => $workExps,
            'schools' => $schoolBgItems
        ];
    }

    private string $filepath = '';
}