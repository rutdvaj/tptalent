<?php
/**
 * Content for the Solutions pages, keyed by page slug — same shape/
 * pattern as inc/service-content.php. Content lives in ACF
 * (group_tp_solution_page in inc/acf-fields.php); this array is the
 * fallback (see tp_get_solution_page_content()) and the source data the
 * bootstrap function pushes into ACF for each page.
 *
 * All copy below is the content the client provided, not placeholder
 * text. Only pages with real content get a bootstrapped page + ACF push
 * (see tp_bootstrap_solution_pages() in inc/setup.php) — the remaining
 * planned solutions (Executive Search, Recruitment Process Outsourcing)
 * are added here once their copy is provided.
 */

if (!defined('ABSPATH')) exit;

function tp_solution_content($slug) {
    $all = tp_solution_content_all();
    return isset($all[$slug]) ? $all[$slug] : null;
}

function tp_solution_content_all() {
    static $data = null;
    if ($data !== null) return $data;

    $data = [

        'permanent-staffing' => [
            'solution_name' => 'Permanent Staffing',
            'headline' => 'Permanent Staffing That Builds Stronger Teams for Long-Term Success',
            'subhead' => 'Hire exceptional talent that stays, grows, and delivers lasting business value.',
            'steps_intro' => 'Building a high-performing workforce starts with hiring the right people. Our permanent staffing process focuses on understanding your business, identifying qualified professionals, and helping you make confident hiring decisions that support long-term growth.',
            'steps' => [
                ['num' => '01', 'title' => 'Understand Your Hiring Goals', 'body' => 'We learn about your business, team structure, culture, and hiring objectives to define the ideal candidate profile.'],
                ['num' => '02', 'title' => 'Source Qualified Professionals', 'body' => 'Using our extensive talent network and targeted search strategies, we identify candidates with the right skills and experience.'],
                ['num' => '03', 'title' => 'Screen & Shortlist Candidates', 'body' => 'Every candidate is evaluated through technical screening, experience validation, and cultural fit assessments before recommendation.'],
                ['num' => '04', 'title' => 'Support Hiring & Onboarding', 'body' => 'We coordinate interviews, assist throughout the selection process, and help ensure a smooth transition into your organization.'],
            ],
        ],

        'contract-staffing' => [
            'solution_name' => 'Contract Staffing',
            'headline' => 'Contract Staffing That Keeps Projects Moving Without Delays',
            'subhead' => 'Access skilled professionals exactly when your business needs them.',
            'steps_intro' => 'When projects demand immediate expertise, our contract staffing solutions help you quickly access experienced professionals without the delays of traditional hiring, giving your business the flexibility to scale as priorities change.',
            'steps' => [
                ['num' => '01', 'title' => 'Define Project Requirements', 'body' => 'We assess your project scope, required skills, timelines, and workforce needs to identify the right contract professionals.'],
                ['num' => '02', 'title' => 'Deploy Specialized Talent', 'body' => 'Our recruiters quickly source experienced professionals who are ready to contribute from day one.'],
                ['num' => '03', 'title' => 'Manage Workforce Flexibility', 'body' => 'We help you scale contract teams up or down as project requirements evolve, ensuring operational agility.'],
                ['num' => '04', 'title' => 'Ensure Successful Delivery', 'body' => 'We provide continuous support throughout the engagement to help maintain productivity and achieve project goals.'],
            ],
        ],

    ];

    return $data;
}
