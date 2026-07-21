<?php
/**
 * Content for the Industries pages, keyed by page slug — same shape as
 * inc/service-content.php. ACF-backed (group_tp_industry_page in
 * inc/acf-fields.php) via tp_get_industry_page_content(); this array is
 * the fallback and also the source data the bootstrap pushes into ACF.
 */

if (!defined('ABSPATH')) exit;

function tp_industry_content($slug) {
    $all = tp_industry_content_all();
    return isset($all[$slug]) ? $all[$slug] : null;
}

function tp_industry_content_all() {
    static $data = null;
    if ($data !== null) return $data;

    $data = [

        'healthcare-life-sciences' => [
            'industry_name' => 'Healthcare & Life Sciences',
            'headline' => 'Healthcare Teams Built for Better Patient Outcomes',
            'subhead' => 'From hospitals and pharmaceutical companies to healthcare technology providers, we help organizations build specialist teams that improve patient care, accelerate innovation, and navigate an increasingly complex healthcare landscape.',
            'prob_heading' => 'Healthcare Never Stops. Neither Do Workforce Challenges.',
            'prob_intro' => 'Healthcare organizations are expected to deliver exceptional patient care while embracing digital health, meeting regulatory requirements, and managing growing workforce shortages. Finding professionals with the right clinical expertise, technical capabilities, and cultural fit has become one of the industry’s biggest competitive advantages.',
            'problems' => [
                ['heading' => 'Critical Talent Shortages', 'text' => 'Demand for experienced healthcare professionals continues to outpace supply, making specialist hiring increasingly competitive.', 'severity' => 'Critical'],
                ['heading' => 'Balancing Innovation with Compliance', 'text' => 'Organizations must adopt modern technologies without compromising patient safety or regulatory standards.', 'severity' => 'High'],
                ['heading' => 'Digital Healthcare Skills Gap', 'text' => 'Healthcare is rapidly adopting AI, analytics, and connected technologies, but experienced professionals remain difficult to find.', 'severity' => 'High'],
                ['heading' => 'Retention Is Becoming Harder', 'text' => 'Burnout, changing workforce expectations, and increasing demand make retaining experienced professionals a growing challenge.', 'severity' => 'High'],
                ['heading' => 'Scaling Without Compromising Quality', 'text' => 'Whether expanding facilities or launching new initiatives, organizations need teams that can grow without sacrificing patient outcomes.', 'severity' => 'Moderate'],
            ],
            'sol_heading' => 'Talent Strategies Designed Around Healthcare',
            'sol_intro' => 'We combine industry expertise with proven recruitment processes to help healthcare organizations secure professionals who strengthen patient care, operational resilience, and long-term innovation.',
            'solutions' => [
                ['title' => 'Specialized Healthcare Recruitment', 'body' => 'Recruit experienced clinical, technical, healthcare IT, life sciences, and leadership professionals through targeted industry-focused hiring.', 'image' => ''],
                ['title' => 'Flexible Workforce Solutions', 'body' => 'Scale permanent teams, project-based hiring, or dedicated recruitment models to meet changing operational and business demands.', 'image' => ''],
                ['title' => 'Future-Ready Talent Planning', 'body' => 'Build teams equipped to support AI adoption, digital transformation, medical technologies, and evolving healthcare delivery models.', 'image' => ''],
            ],
            'testimonial_quote' => 'Tecnoprism Talent understood our hiring challenges from day one. Their ability to identify professionals who aligned with both our technical requirements and organizational culture significantly improved our recruitment outcomes.',
            'testimonial_name' => 'Sarah Mitchell',
            'testimonial_title' => 'Director of Human Resources · Healthcare Technology Organization',
            'faq_heading' => 'Frequently Asked Questions',
            'faq_intro' => 'Answers to common questions about our healthcare recruitment capabilities.',
            'faqs' => [
                ['q' => 'Do you recruit for both clinical and non-clinical healthcare roles?', 'a' => 'Yes. We recruit across clinical operations, healthcare IT, life sciences, digital health, medical devices, research, and leadership positions.'],
                ['q' => 'Can you support large healthcare hiring initiatives?', 'a' => 'Absolutely. We help organizations scale from specialist placements to enterprise-wide workforce expansion.'],
                ['q' => 'Do you recruit for healthcare technology companies?', 'a' => 'Yes. We support organizations working in healthtech, medical software, AI, diagnostics, and connected healthcare solutions.'],
                ['q' => 'How do you ensure candidate quality?', 'a' => 'Every candidate is evaluated through structured screening, technical validation, experience assessment, and role-specific qualification processes.'],
                ['q' => 'Can you provide contract and permanent professionals?', 'a' => 'Yes. We offer permanent recruitment, contract staffing, dedicated recruiters, and project-based hiring models.'],
                ['q' => 'Do you recruit internationally?', 'a' => 'Yes. We support organizations hiring across multiple regions through our global recruitment capabilities.'],
            ],
            'cta_subhead' => 'Build healthcare teams that improve patient outcomes today while preparing your organization for tomorrow.',
        ],

    ];

    return $data;
}
