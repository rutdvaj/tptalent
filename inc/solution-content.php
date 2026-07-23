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
 * (see tp_bootstrap_solution_pages()/tp_bootstrap_solution_pages_v2() in
 * inc/setup.php).
 *
 * Executive Search is keyed 'executive-search-solution', not the plain
 * 'executive-search' slug — that slug already belongs to the Executive
 * Search SERVICE page (inc/service-content.php), and group_tp_solution_page
 * shares field names (headline, subhead, step_1..4) with
 * group_tp_service_page. Reusing the slug would mean both bootstraps
 * writing to the same post's ACF fields under the same field names,
 * silently corrupting one with the other's content — the exact bug hit
 * earlier with the Digital Transformation industry page (see
 * tp_bootstrap_fix_digital_transformation_collision() in
 * inc/acf-fields.php). Same fix, applied up front this time.
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

        'executive-search-solution' => [
            'solution_name' => 'Executive Search',
            'headline' => 'Executive Search That Finds Leaders Who Drive Lasting Business Growth',
            'subhead' => 'Secure proven leaders with the vision to move your business forward.',
            'steps_intro' => 'Leadership hires shape the future of an organization. Our executive search process identifies experienced leaders with the strategic vision, industry expertise, and cultural alignment needed to deliver long-term business success.',
            'steps' => [
                ['num' => '01', 'title' => 'Define Leadership Requirements', 'body' => 'We work closely with your leadership team to understand business goals, organizational culture, and the qualities required for the role.'],
                ['num' => '02', 'title' => 'Identify Executive Talent', 'body' => 'Through targeted market research and our executive network, we engage accomplished leaders with proven industry experience.'],
                ['num' => '03', 'title' => 'Evaluate Leadership Fit', 'body' => 'Every executive is assessed for leadership capability, strategic thinking, domain expertise, and organizational alignment.'],
                ['num' => '04', 'title' => 'Support Executive Placement', 'body' => 'From interviews to final negotiations and onboarding, we help ensure a seamless leadership transition.'],
            ],
        ],

        'recruitment-process-outsourcing' => [
            'solution_name' => 'Recruitment Process Outsourcing',
            'headline' => 'Recruitment Process Outsourcing That Scales Hiring With Confidence',
            'subhead' => 'Extend your recruitment team without increasing internal complexity.',
            'steps_intro' => 'As hiring demands grow, internal recruitment teams often struggle to keep pace. Our Recruitment Process Outsourcing (RPO) solutions integrate with your business to streamline hiring, improve efficiency, and deliver consistent recruitment outcomes at scale.',
            'steps' => [
                ['num' => '01', 'title' => 'Understand Your Hiring Strategy', 'body' => 'We evaluate your recruitment goals, hiring challenges, and workforce plans to design an RPO solution tailored to your business.'],
                ['num' => '02', 'title' => 'Integrate With Your Team', 'body' => 'Our recruiters become an extension of your organization, managing sourcing, screening, coordination, and candidate communication.'],
                ['num' => '03', 'title' => 'Optimize Recruitment Operations', 'body' => 'We improve hiring workflows, reduce time-to-fill, and create a consistent recruitment experience across every role.'],
                ['num' => '04', 'title' => 'Measure & Scale Performance', 'body' => 'Using recruitment insights and performance metrics, we continuously refine hiring strategies as your workforce needs evolve.'],
            ],
        ],

    ];

    return $data;
}
