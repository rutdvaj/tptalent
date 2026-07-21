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
                ['q' => 'What healthcare professionals can Tecnoprism Talent recruit?', 'a' => 'We recruit clinical, healthcare IT, life sciences, medical device, and healthcare leadership professionals.'],
                ['q' => 'Can Tecnoprism Talent support healthcare workforce expansion?', 'a' => 'Yes, we help hospitals, healthcare providers, and life sciences companies scale skilled teams efficiently.'],
                ['q' => 'How does Tecnoprism Talent assess healthcare candidates?', 'a' => 'Every candidate is screened for technical expertise, industry experience, and role-specific qualifications.'],
                ['q' => 'What healthcare recruitment models do you offer?', 'a' => 'We offer permanent hiring, contract staffing, Recruiter-on-Premise, and project-based recruitment solutions.'],
            ],
            'cta_subhead' => 'Build healthcare teams that improve patient outcomes today while preparing your organization for tomorrow.',
        ],

        'technology-ites' => [
            'industry_name' => 'Technology & ITES',
            'headline' => 'Technology Talent That Accelerates Innovation',
            'subhead' => 'From fast-growing startups to global enterprises, we help technology companies build high-performing teams that deliver products faster, embrace emerging technologies, and drive long-term digital growth.',
            'prob_heading' => 'Innovation Moves Fast. Hiring Has to Move Faster.',
            'prob_intro' => 'Technology companies operate in one of the world’s fastest-changing industries, where access to skilled talent directly impacts product delivery, innovation, and competitive advantage. Finding professionals with the right technical expertise has become one of the biggest barriers to sustainable growth.',
            'problems' => [
                ['heading' => 'Specialized Skills Are Hard to Find', 'text' => 'Demand for experienced software, cloud, AI, and cybersecurity professionals continues to exceed supply.', 'severity' => 'Critical'],
                ['heading' => 'Hiring Delays Slow Product Delivery', 'text' => 'Extended recruitment timelines impact product launches, customer expectations, and business growth.', 'severity' => 'High'],
                ['heading' => 'Technology Evolves Rapidly', 'text' => 'Organizations must continuously hire specialists in emerging technologies to remain competitive.', 'severity' => 'High'],
                ['heading' => 'Scaling Engineering Teams Is Challenging', 'text' => 'Building high-performing development teams while maintaining quality becomes increasingly difficult as businesses grow.', 'severity' => 'High'],
                ['heading' => 'Global Competition for Talent', 'text' => 'Businesses compete worldwide for experienced technology professionals across every major discipline.', 'severity' => 'Moderate'],
            ],
            'sol_heading' => 'Recruitment Strategies Built for Modern Technology Companies',
            'sol_intro' => 'We connect technology organizations with skilled professionals who accelerate innovation, strengthen engineering teams, and support long-term business growth.',
            'solutions' => [
                ['title' => 'Specialized Technology Recruitment', 'body' => 'Recruit software engineers, AI specialists, cloud architects, DevOps engineers, ERP consultants, cybersecurity experts, and data professionals.', 'image' => ''],
                ['title' => 'Flexible Hiring Models', 'body' => 'Scale teams through permanent recruitment, contract staffing, Recruiter-on-Premise, or project-based hiring.', 'image' => ''],
                ['title' => 'Future-Ready Workforce Planning', 'body' => 'Build technology teams prepared for AI, cloud computing, automation, and enterprise digital transformation.', 'image' => ''],
            ],
            'testimonial_quote' => 'Tecnoprism Talent consistently delivered highly skilled technology professionals who helped us accelerate product development without compromising quality.',
            'testimonial_name' => 'David Chen',
            'testimonial_title' => 'VP of Engineering · Enterprise Software Company',
            'faq_heading' => 'Frequently Asked Questions',
            'faq_intro' => 'Answers to common questions about our technology recruitment capabilities.',
            'faqs' => [
                ['q' => 'What technology professionals can Tecnoprism Talent recruit?', 'a' => 'We recruit software engineers, AI specialists, cloud experts, DevOps engineers, ERP consultants, and cybersecurity professionals.'],
                ['q' => 'Can Tecnoprism Talent help scale software development teams?', 'a' => 'Yes, we rapidly build engineering teams for startups, enterprises, and digital transformation projects.'],
                ['q' => 'Do you recruit for AI and cloud computing roles?', 'a' => 'Yes, we specialize in AI, Generative AI, Machine Learning, Cloud, Data, and Automation professionals.'],
                ['q' => 'How quickly can Tecnoprism Talent fill technology roles?', 'a' => 'Our extensive technology talent network helps reduce hiring timelines for critical technical positions.'],
            ],
            'cta_subhead' => 'Build technology teams that innovate faster, scale smarter, and stay ahead of what’s next.',
        ],

        'manufacturing-energy' => [
            'industry_name' => 'Manufacturing & Energy',
            'headline' => 'Manufacturing Talent That Keeps Production Moving',
            'subhead' => 'Helping manufacturers and energy organizations build skilled workforces that improve productivity, strengthen operations, and support the future of industrial innovation.',
            'prob_heading' => 'Strong Operations Begin With Strong Teams',
            'prob_intro' => 'Manufacturing and energy organizations are under constant pressure to increase efficiency, embrace automation, and meet growing production demands. Securing experienced engineering and technical professionals has become essential to maintaining operational excellence and long-term competitiveness.',
            'problems' => [
                ['heading' => 'Skilled Talent Is in Short Supply', 'text' => 'Experienced engineers, technicians, and industrial specialists remain difficult to recruit in a competitive market.', 'severity' => 'Critical'],
                ['heading' => 'Modern Manufacturing Requires New Skills', 'text' => 'Automation, robotics, and Industry 4.0 demand expertise beyond traditional manufacturing roles.', 'severity' => 'High'],
                ['heading' => 'Production Downtime Is Costly', 'text' => 'Critical vacancies can impact productivity, delivery schedules, and overall operational performance.', 'severity' => 'Critical'],
                ['heading' => 'Compliance and Safety Standards Are Rising', 'text' => 'Industrial environments require professionals who understand strict operational and regulatory requirements.', 'severity' => 'High'],
                ['heading' => 'Scaling Operations Without Disruption', 'text' => 'Growing production capacity requires flexible workforce strategies that maintain quality and efficiency.', 'severity' => 'Moderate'],
            ],
            'sol_heading' => 'Workforce Solutions Built for Industrial Growth',
            'sol_intro' => 'We help manufacturing and energy organizations recruit professionals who strengthen operations, improve productivity, and support long-term business success.',
            'solutions' => [
                ['title' => 'Industrial & Engineering Recruitment', 'body' => 'Recruit manufacturing engineers, automation specialists, production managers, quality professionals, and technical leaders.', 'image' => ''],
                ['title' => 'Scalable Workforce Solutions', 'body' => 'Expand your workforce through permanent recruitment, contract staffing, Recruiter-on-Premise, or project-based hiring.', 'image' => ''],
                ['title' => 'Industry 4.0 Talent', 'body' => 'Build future-ready teams with expertise in robotics, automation, IoT, AI, and smart manufacturing technologies.', 'image' => ''],
            ],
            'testimonial_quote' => 'Tecnoprism Talent provided engineering professionals who integrated quickly into our operations and helped improve production efficiency across multiple facilities.',
            'testimonial_name' => 'Michael Reynolds',
            'testimonial_title' => 'Operations Director · Global Manufacturing Company',
            'faq_heading' => 'Frequently Asked Questions',
            'faq_intro' => 'Answers to common questions about our manufacturing and energy recruitment capabilities.',
            'faqs' => [
                ['q' => 'What manufacturing and engineering roles can Tecnoprism Talent recruit?', 'a' => 'We recruit manufacturing engineers, automation specialists, production managers, quality engineers, and industrial leaders.'],
                ['q' => 'Can Tecnoprism Talent help manufacturers scale production teams?', 'a' => 'Yes, we support workforce expansion for new facilities, production growth, and industrial projects.'],
                ['q' => 'Do you recruit for Industry 4.0 and smart manufacturing?', 'a' => 'Yes, we recruit specialists in robotics, automation, IoT, AI, and advanced manufacturing technologies.'],
                ['q' => 'How do you ensure manufacturing candidates are job-ready?', 'a' => 'Candidates are evaluated for technical skills, industry experience, safety awareness, and operational readiness.'],
            ],
            'cta_subhead' => 'Build manufacturing and energy teams ready to power the next generation of industrial growth.',
        ],

        'bfsi' => [
            'industry_name' => 'BFSI',
            'headline' => 'BFSI Talent Built for Trust, Compliance & Growth',
            'subhead' => 'From banks and insurance providers to fintech innovators, we help financial organizations build specialist teams that strengthen compliance, accelerate digital transformation, and deliver exceptional customer experiences.',
            'prob_heading' => 'Financial Services Are Changing Faster Than Ever',
            'prob_intro' => 'The financial services industry is balancing regulatory pressure, cybersecurity risks, evolving customer expectations, and rapid technological innovation. Building teams with the expertise to navigate this changing landscape has become essential for sustainable growth.',
            'problems' => [
                ['heading' => 'Keeping Pace with Regulations', 'text' => 'Regulatory requirements continue to evolve, demanding professionals who understand both compliance and business operations.', 'severity' => 'Critical'],
                ['heading' => 'Growing Cybersecurity Threats', 'text' => 'Financial institutions require highly skilled specialists to protect sensitive data and maintain customer trust.', 'severity' => 'Critical'],
                ['heading' => 'Digital Banking Expectations', 'text' => 'Customers expect seamless digital experiences while organizations modernize legacy banking systems.', 'severity' => 'High'],
                ['heading' => 'Competition for Specialist Talent', 'text' => 'Experienced professionals across fintech, risk, analytics, and enterprise technology remain in high demand.', 'severity' => 'High'],
                ['heading' => 'Scaling Innovation Responsibly', 'text' => 'Organizations must embrace AI and automation without compromising governance or operational stability.', 'severity' => 'Moderate'],
            ],
            'sol_heading' => 'Recruitment Strategies Built for Financial Services',
            'sol_intro' => 'We help BFSI organizations build resilient, future-ready teams that combine technical expertise, regulatory knowledge, and business insight.',
            'solutions' => [
                ['title' => 'Specialist Financial Recruitment', 'body' => 'Recruit professionals across banking, insurance, fintech, risk, compliance, cybersecurity, and enterprise technology.', 'image' => ''],
                ['title' => 'Flexible Workforce Models', 'body' => 'Scale permanent teams, project specialists, and dedicated recruitment engagements to meet evolving business priorities.', 'image' => ''],
                ['title' => 'Digital Transformation Talent', 'body' => 'Support modernization initiatives with experts in AI, cloud, analytics, automation, and enterprise platforms.', 'image' => ''],
            ],
            'testimonial_quote' => 'Their ability to understand our regulatory environment while sourcing exceptional technology professionals made them a valuable long-term recruitment partner.',
            'testimonial_name' => 'James Walker',
            'testimonial_title' => 'Head of Technology · Financial Services Organization',
            'faq_heading' => 'Frequently Asked Questions',
            'faq_intro' => 'Everything you need to know about our BFSI recruitment capabilities.',
            'faqs' => [
                ['q' => 'What BFSI professionals can Tecnoprism Talent recruit?', 'a' => 'We recruit banking, insurance, fintech, compliance, risk, cybersecurity, and financial technology professionals.'],
                ['q' => 'Can Tecnoprism Talent support fintech hiring?', 'a' => 'Yes, we help fintech companies recruit specialists in digital banking, AI, payments, cloud, and cybersecurity.'],
                ['q' => 'Do you recruit for regulated financial institutions?', 'a' => 'Yes, we source professionals experienced in banking, insurance, and highly regulated financial environments.'],
                ['q' => 'What recruitment solutions do you offer for BFSI companies?', 'a' => 'We provide permanent hiring, contract staffing, Recruiter-on-Premise, and project-based recruitment.'],
            ],
            'cta_subhead' => 'Build financial teams prepared for today’s regulations and tomorrow’s opportunities.',
        ],

        'automotive-aerospace' => [
            'industry_name' => 'Automotive & Aerospace',
            'headline' => 'Automotive & Aerospace Teams Built to Drive Innovation',
            'subhead' => 'Helping automotive manufacturers, aerospace companies, and mobility innovators build engineering teams ready for the next generation of transportation.',
            'prob_heading' => 'Innovation Depends on Engineering Excellence',
            'prob_intro' => 'The automotive and aerospace industries are rapidly advancing through electrification, automation, connected systems, and advanced manufacturing. Organizations need highly specialized professionals capable of delivering innovation without compromising quality, safety, or compliance.',
            'problems' => [
                ['heading' => 'Engineering Skills Are Evolving', 'text' => 'Emerging technologies require expertise that traditional recruitment pipelines often struggle to provide.', 'severity' => 'Critical'],
                ['heading' => 'Innovation Cycles Are Accelerating', 'text' => 'Products are expected to reach market faster while becoming increasingly complex.', 'severity' => 'High'],
                ['heading' => 'Quality Cannot Be Compromised', 'text' => 'Every engineering decision must satisfy rigorous safety, regulatory, and performance standards.', 'severity' => 'Critical'],
                ['heading' => 'Global Competition for Talent', 'text' => 'Experienced engineers remain one of the industry’s most competitive resources.', 'severity' => 'High'],
                ['heading' => 'Scaling Advanced Manufacturing', 'text' => 'Organizations require multidisciplinary teams capable of supporting automation and smart manufacturing initiatives.', 'severity' => 'Moderate'],
            ],
            'sol_heading' => 'Engineering Talent for the Future of Mobility',
            'sol_intro' => 'We connect automotive and aerospace organizations with professionals who combine technical expertise, industry knowledge, and innovation-driven thinking.',
            'solutions' => [
                ['title' => 'Specialized Engineering Recruitment', 'body' => 'Recruit mechanical, electrical, embedded systems, manufacturing, quality, and aerospace engineering professionals.', 'image' => ''],
                ['title' => 'Project-Based Workforce Scaling', 'body' => 'Expand engineering capacity quickly to support product launches, modernization initiatives, and operational growth.', 'image' => ''],
                ['title' => 'Technology-Focused Hiring', 'body' => 'Build future-ready teams across EV, autonomous systems, robotics, embedded software, AI, and digital manufacturing.', 'image' => ''],
            ],
            'testimonial_quote' => 'Tecnoprism Talent consistently identified engineers with the technical expertise and collaborative mindset our innovation programs demanded.',
            'testimonial_name' => 'Daniel Foster',
            'testimonial_title' => 'Engineering Director · Global Automotive Manufacturer',
            'faq_heading' => 'Frequently Asked Questions',
            'faq_intro' => 'Answers to common questions about automotive and aerospace recruitment.',
            'faqs' => [
                ['q' => 'What automotive and aerospace professionals can Tecnoprism Talent recruit?', 'a' => 'We recruit mechanical, electrical, embedded, manufacturing, aerospace, and quality engineering professionals.'],
                ['q' => 'Can Tecnoprism Talent recruit for EV and autonomous vehicle projects?', 'a' => 'Yes, we help build engineering teams for electric vehicles, connected mobility, and autonomous technologies.'],
                ['q' => 'Do you support large automotive manufacturing projects?', 'a' => 'Yes, we scale engineering and manufacturing teams for product development and production expansion.'],
                ['q' => 'How do you evaluate automotive and aerospace candidates?', 'a' => 'Candidates are assessed through technical screening, engineering experience, and industry-specific expertise.'],
            ],
            'cta_subhead' => 'Build engineering teams ready to shape the future of mobility.',
        ],

        // Slug deliberately differs from the "Digital Transformation"
        // SERVICE page (inc/service-content.php uses the plain
        // 'digital-transformation' slug already) — same name, different
        // offering. Using the same slug here caused a real collision:
        // get_page_by_path() found the existing service page, so no new
        // page was created, and the two ACF field groups share several
        // field names (headline, subhead, prob_heading, problem_N,
        // cta_subhead), so pushing industry content overwrote the
        // service page's data. See tp_bootstrap_fix_digital_transformation_collision().
        'digital-transformation-industry' => [
            'industry_name' => 'Digital Transformation',
            'headline' => 'Digital Transformation Teams That Turn Change Into Growth',
            'subhead' => 'Helping organizations build multidisciplinary teams that accelerate modernization, unlock innovation, and deliver meaningful business transformation.',
            'prob_heading' => 'Technology Alone Doesn’t Transform Businesses',
            'prob_intro' => 'Successful digital transformation requires more than new technology. It demands the right people, modern processes, and specialized expertise capable of turning ambitious transformation strategies into measurable business outcomes.',
            'problems' => [
                ['heading' => 'Legacy Systems Slow Progress', 'text' => 'Outdated technologies reduce agility and make innovation increasingly difficult.', 'severity' => 'Critical'],
                ['heading' => 'Digital Skills Remain Limited', 'text' => 'Organizations struggle to recruit professionals experienced in cloud, AI, automation, and modern enterprise technologies.', 'severity' => 'High'],
                ['heading' => 'Transformation Lacks Alignment', 'text' => 'Technology initiatives often fail when business strategy and execution become disconnected.', 'severity' => 'High'],
                ['heading' => 'Scaling Innovation Is Difficult', 'text' => 'Organizations need experienced leaders capable of managing transformation across multiple business functions.', 'severity' => 'Moderate'],
                ['heading' => 'Change Management Becomes a Bottleneck', 'text' => 'Employee adoption remains one of the biggest challenges to successful transformation.', 'severity' => 'High'],
            ],
            'sol_heading' => 'Building Teams That Deliver Transformation',
            'sol_intro' => 'We help organizations recruit specialists who combine technical expertise, business understanding, and execution capability to deliver lasting digital transformation.',
            'solutions' => [
                ['title' => 'Digital Transformation Specialists', 'body' => 'Recruit professionals across cloud, AI, enterprise applications, automation, cybersecurity, and data platforms.', 'image' => ''],
                ['title' => 'Scalable Recruitment Models', 'body' => 'Build dedicated transformation teams through permanent, contract, or project-based hiring.', 'image' => ''],
                ['title' => 'Long-Term Workforce Strategy', 'body' => 'Create future-ready organizations with recruitment strategies aligned to evolving technology and business priorities.', 'image' => ''],
            ],
            'testimonial_quote' => 'Their recruitment expertise helped us build a transformation team capable of delivering complex modernization initiatives on schedule.',
            'testimonial_name' => 'Emily Carter',
            'testimonial_title' => 'Chief Digital Officer · Enterprise Technology Organization',
            'faq_heading' => 'Frequently Asked Questions',
            'faq_intro' => 'Everything you need to know about digital transformation recruitment.',
            'faqs' => [
                ['q' => 'What digital transformation professionals can Tecnoprism Talent recruit?', 'a' => 'We recruit AI, cloud, cybersecurity, automation, enterprise application, and data analytics professionals.'],
                ['q' => 'Can Tecnoprism Talent build digital transformation teams?', 'a' => 'Yes, we build multidisciplinary teams for enterprise modernization and digital transformation initiatives.'],
                ['q' => 'Do you recruit AI, cloud, and automation specialists?', 'a' => 'Yes, we recruit experts across AI, Generative AI, Cloud Computing, Automation, and Data Engineering.'],
                ['q' => 'How does Tecnoprism Talent support digital transformation projects?', 'a' => 'We align recruitment strategies with your technology roadmap and long-term business transformation goals.'],
            ],
            'cta_subhead' => 'Build the people behind your next transformation, not just the technology.',
        ],

    ];

    return $data;
}
