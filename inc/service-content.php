<?php
/**
 * Content for the service pages, keyed by page slug — mirrors the shape
 * tp_get_section() uses for the homepage. Each page's content now lives
 * in ACF (group_tp_service_page in inc/acf-fields.php); this array is
 * the fallback (see tp_get_service_page_content()) and also the source
 * data the bootstrap functions push into ACF for each page.
 *
 * All copy below is taken directly from the design handoff files / the
 * content the client provided, not placeholder text.
 */

if (!defined('ABSPATH')) exit;

function tp_service_content($slug) {
    $all = tp_service_content_all();
    return isset($all[$slug]) ? $all[$slug] : null;
}

function tp_service_content_all() {
    static $data = null;
    if ($data !== null) return $data;

    $data = [

        'executive-search' => [
            'headline' => 'Leadership hires that change the trajectory',
            'subhead'  => 'Competency-first search for leaders who raise the ceiling.',
            'prob_heading' => 'Six ways executive hiring goes wrong',
            'problems' => [
                ['heading' => 'The seat stays empty for months', 'text' => 'Every week without a leader stalls decisions, drains the team and compounds into quarters of lost momentum.'],
                ['heading' => 'Great on paper, wrong in the room', 'text' => 'Résumés pass every screen — then the competency gaps surface six months after the hire, when it’s most expensive.'],
                ['heading' => 'Your network is everyone’s network', 'text' => 'The same recycled shortlist circulates between agencies. The leaders you actually need never answer job ads.'],
                ['heading' => 'The hire leaves in year one', 'text' => 'No transition plan, no onboarding support — the search restarts, and this time with a credibility cost attached.'],
                ['heading' => 'Compensation is guesswork', 'text' => 'Offers built on stale benchmarks either overpay or lose the candidate at the final step.'],
                ['heading' => 'No honest read on the market', 'text' => 'You commit to a search blind — with no clear picture of who is available, and at what cost.'],
            ],
            'steps' => [
                ['num' => '01', 'title' => 'Define the mandate', 'body' => 'We sit with the board or hiring leader to translate strategy into a competency scorecard — what this leader must actually do in the first 18 months.'],
                ['num' => '02', 'title' => 'Map & assess', 'body' => 'Data-led market mapping across 21 countries surfaces active and passive leaders; each is assessed against the scorecard, not a gut feel.'],
                ['num' => '03', 'title' => 'Present & pressure-test', 'body' => 'A weekly evidence-backed shortlist with structured interviews, references and case exercises — you see the reasoning, not just the résumé.'],
                ['num' => '04', 'title' => 'Onboard & retain', 'body' => 'Offer design, transition planning and 90-day onboarding support so the placement sticks — the reason 95% of our executives stay.'],
            ],
            'cta_heading' => 'The seat is empty. The clock is running.',
            'cta_subhead' => 'Tell us about the role — we’ll come back within one business day with an honest read on the market.',
        ],

        'payrolling-services' => [
            'headline' => 'Payroll that closes clean, every cycle',
            'subhead'  => 'Automated, compliant payroll for financial services and beyond.',
            'prob_heading' => 'Six ways payroll drains the business',
            'problems' => [
                ['heading' => 'Every cycle is a fire drill', 'text' => 'Manual reconciliation eats the last week of every month — and the finance team’s best hours with it.'],
                ['heading' => 'Compliance shifts under your feet', 'text' => 'SOX, GAAP and multi-country rules change faster than the spreadsheet that’s supposed to track them.'],
                ['heading' => 'One error costs more than money', 'text' => 'A single wrong run damages employee trust, triggers audit scrutiny and puts retention on the line.'],
                ['heading' => 'Growth breaks the framework', 'text' => 'Every merger, expansion and hiring sprint means re-implementation — payroll becomes the drag on the deal.'],
                ['heading' => 'No visibility into the numbers', 'text' => 'Compensation, tax and cost data sit in silos — leadership flies blind on one of its largest expenses.'],
                ['heading' => 'Complex pay breaks the system', 'text' => 'Bonuses, equity and incentives get patched in by hand, and every exception multiplies the risk of error.'],
            ],
            'steps' => [
                ['num' => '01', 'title' => 'Audit the current cycle', 'body' => 'We map your pay structures, jurisdictions and compliance obligations — and flag where errors and delays are hiding today.'],
                ['num' => '02', 'title' => 'Configure & migrate', 'body' => 'Your compensation tiers, incentives and tax rules move into an automated framework — validated against parallel runs before cutover.'],
                ['num' => '03', 'title' => 'Run & reconcile', 'body' => 'Every cycle processes with automated checks and specialist review; dashboards surface compensation, tax filings and trends live.'],
                ['num' => '04', 'title' => 'Adapt as you grow', 'body' => 'Mergers, expansions and new geographies fold into the same framework — compliance coverage extends without re-implementation.'],
            ],
            'cta_heading' => 'Payroll isn’t a hurdle. It’s an opportunity.',
            'cta_subhead' => 'Walk us through your current cycle — we’ll show you where the errors, delays and compliance risk hide, within one business day.',
        ],

        'virtual-assistance' => [
            'headline' => 'Hand off the busywork. Keep the momentum.',
            'subhead'  => 'Trained assistants who own your operations, end to end.',
            'prob_heading' => 'Six ways operations eat your week',
            'problems' => [
                ['heading' => 'Your calendar runs your company', 'text' => 'Scheduling, inboxes and follow-ups swallow whole days — the work that grows the business gets what’s left.'],
                ['heading' => 'Delegation trades quality for time', 'text' => 'Handoffs come back wrong, so you stop handing off — and quietly become the bottleneck for everything.'],
                ['heading' => 'Headcount before the model is proven', 'text' => 'Full-time hires carry part-time load. Fixed costs stack up long before the revenue justifies them.'],
                ['heading' => 'Support breaks at every peak', 'text' => 'Launches and seasonal spikes overwhelm the team — then it’s quiet, and you’re paying for idle capacity.'],
                ['heading' => 'Security is an afterthought', 'text' => 'Sensitive data passes through untrained hands — confidentiality becomes a risk you can’t see until it’s breached.'],
                ['heading' => 'Time zones stall everything', 'text' => 'Work waits overnight for answers — distributed teams lose a full day to every simple handoff.'],
            ],
            'steps' => [
                ['num' => '01', 'title' => 'Map the load', 'body' => 'A short working session inventories what fills your team’s week — and which of it can be owned end-to-end by an assistant.'],
                ['num' => '02', 'title' => 'Match & brief', 'body' => 'We pair you with assistants trained in your sector’s workflows, with documented processes written before day one.'],
                ['num' => '03', 'title' => 'Run with QA', 'body' => 'Work runs inside a quality layer — output is checked before it reaches you, and accuracy is tracked, not assumed.'],
                ['num' => '04', 'title' => 'Scale & adjust', 'body' => 'Ramp support up for launches and seasons, down when it’s quiet. The engagement flexes monthly, not annually.'],
            ],
            'cta_heading' => 'Your calendar shouldn’t run your company',
            'cta_subhead' => 'Tell us what eats your week — we’ll map which of it an assistant can own, within one business day.',
        ],

        'talent-acquisition' => [
            'headline' => 'Talent Acquisition That Finds the Right Fit, Faster',
            'subhead'  => 'Build high-performing teams with strategic hiring solutions that connect you with skilled professionals faster.',
            'prob_heading' => 'Hiring Challenges That Slow Business Growth',
            'problems' => [
                ['heading' => 'Critical roles remain vacant', 'text' => 'Long hiring cycles delay projects and impact business performance.'],
                ['heading' => 'Poor candidate quality', 'text' => 'Unqualified applicants consume valuable hiring time.'],
                ['heading' => 'High employee turnover', 'text' => 'Frequent attrition increases hiring costs and disrupts continuity.'],
                ['heading' => 'Limited access to niche talent', 'text' => 'Specialized professionals are difficult to source through traditional hiring.'],
                ['heading' => 'Slow recruitment processes', 'text' => 'Manual workflows delay decision-making and candidate engagement.'],
                ['heading' => 'Scaling becomes difficult', 'text' => 'Rapid business growth outpaces internal recruitment capabilities.'],
            ],
            'steps' => [
                ['num' => '01', 'title' => 'Understand Your Hiring Goals', 'body' => 'We align recruitment strategies with your business objectives.'],
                ['num' => '02', 'title' => 'Source Qualified Talent', 'body' => 'Our recruiters identify and engage pre-screened professionals.'],
                ['num' => '03', 'title' => 'Evaluate & Shortlist', 'body' => 'Every candidate is assessed for technical skills and cultural fit.'],
                ['num' => '04', 'title' => 'Support Successful Hiring', 'body' => 'We manage the process until the right candidate joins your team.'],
            ],
            'cta_heading' => 'Build Your Workforce with Confidence',
            'cta_subhead' => 'Partner with Tecnoprism to hire skilled professionals faster and strengthen your workforce.',
        ],

        'recruiter-on-premise' => [
            'headline' => 'Recruiter on Premise, Right Where Hiring Happens',
            'subhead'  => 'Extend your hiring team with dedicated recruiters who work as a seamless part of your organization.',
            'prob_heading' => 'When Internal Recruitment Needs Extra Capacity',
            'problems' => [
                ['heading' => 'Recruiters are overloaded', 'text' => 'Growing hiring demands overwhelm internal HR teams.'],
                ['heading' => 'Hiring lacks consistency', 'text' => 'Different recruiters follow different recruitment practices.'],
                ['heading' => 'Business knowledge is limited', 'text' => 'External recruiters often lack an understanding of company culture.'],
                ['heading' => 'Hiring spikes become unmanageable', 'text' => 'Seasonal and project-based recruitment creates bottlenecks.'],
                ['heading' => 'Candidate experience suffers', 'text' => 'Slow communication causes qualified candidates to drop out.'],
                ['heading' => 'Internal teams lose productivity', 'text' => 'Recruitment takes focus away from strategic HR initiatives.'],
            ],
            'steps' => [
                ['num' => '01', 'title' => 'Embed Dedicated Recruiters', 'body' => 'Our specialists integrate directly with your hiring team.'],
                ['num' => '02', 'title' => 'Align with Your Processes', 'body' => 'Recruitment follows your workflows and employer brand.'],
                ['num' => '03', 'title' => 'Accelerate Candidate Delivery', 'body' => 'Dedicated sourcing improves hiring speed and consistency.'],
                ['num' => '04', 'title' => 'Continuously Optimize Hiring', 'body' => 'Performance metrics help improve recruitment outcomes over time.'],
            ],
            'cta_heading' => 'Extend Your Recruitment Team',
            'cta_subhead' => 'Scale hiring efficiently with experienced recruiters working alongside your organization.',
        ],

        'robotics-automation' => [
            'headline' => 'Robotics & Automation That Keep Work Moving',
            'subhead'  => 'Automate repetitive business processes to improve productivity, accuracy, and operational efficiency.',
            'prob_heading' => 'Manual Processes Limit Business Performance',
            'problems' => [
                ['heading' => 'Repetitive work consumes time', 'text' => 'Employees spend valuable hours on routine manual tasks.'],
                ['heading' => 'Operational errors increase', 'text' => 'Human mistakes affect productivity and compliance.'],
                ['heading' => 'Processes don’t scale', 'text' => 'Manual operations struggle as business demand grows.'],
                ['heading' => 'Systems remain disconnected', 'text' => 'Multiple platforms create inefficient workflows.'],
                ['heading' => 'High operational costs', 'text' => 'Manual execution increases long-term expenses.'],
                ['heading' => 'Slow response times', 'text' => 'Business processes fail to meet customer expectations.'],
            ],
            'steps' => [
                ['num' => '01', 'title' => 'Identify Automation Opportunities', 'body' => 'We assess workflows suitable for automation.'],
                ['num' => '02', 'title' => 'Design Intelligent Solutions', 'body' => 'Automation is tailored to your business processes.'],
                ['num' => '03', 'title' => 'Implement & Integrate', 'body' => 'Bots are deployed across existing enterprise systems.'],
                ['num' => '04', 'title' => 'Monitor & Optimize', 'body' => 'Performance is continuously improved for maximum efficiency.'],
            ],
            'cta_heading' => 'Transform Manual Work into Intelligent Automation',
            'cta_subhead' => 'Streamline operations with scalable automation solutions built for enterprise growth.',
        ],

        'generative-agentic-ai' => [
            'headline' => 'Generative & Agentic AI That Turns Intelligence Into Action',
            'subhead'  => 'Develop intelligent AI solutions that automate workflows, accelerate decision-making, and unlock new business opportunities.',
            'prob_heading' => 'Challenges Preventing Enterprise AI Adoption',
            'problems' => [
                ['heading' => 'AI initiatives lack direction', 'text' => 'Businesses struggle to identify practical AI use cases.'],
                ['heading' => 'Knowledge remains siloed', 'text' => 'Valuable organizational data is difficult to access.'],
                ['heading' => 'Processes require constant manual effort', 'text' => 'Teams spend time on repetitive knowledge-based tasks.'],
                ['heading' => 'Customer expectations are evolving', 'text' => 'Traditional support models struggle to keep pace.'],
                ['heading' => 'AI integration feels complex', 'text' => 'Existing systems make implementation challenging.'],
                ['heading' => 'Innovation moves too slowly', 'text' => 'Delayed adoption reduces competitive advantage.'],
            ],
            'steps' => [
                ['num' => '01', 'title' => 'Identify High-Impact Use Cases', 'body' => 'We prioritize AI initiatives that deliver measurable value.'],
                ['num' => '02', 'title' => 'Develop Intelligent AI Solutions', 'body' => 'Custom AI models and agents are designed around your business.'],
                ['num' => '03', 'title' => 'Integrate with Enterprise Systems', 'body' => 'AI is connected securely with your existing technology stack.'],
                ['num' => '04', 'title' => 'Scale & Continuously Improve', 'body' => 'Models are monitored and refined for long-term business performance.'],
            ],
            'cta_heading' => 'Turn Enterprise AI into Business Value',
            'cta_subhead' => 'Partner with Tecnoprism to build secure, scalable AI solutions that drive measurable outcomes.',
        ],

        'machine-learning' => [
            'headline' => 'Machine Learning That Turns Data Into Foresight',
            'subhead'  => 'Transform enterprise data into intelligent predictions, automation, and better business decisions.',
            'prob_heading' => 'Data Without Intelligence Creates Missed Opportunities',
            'problems' => [
                ['heading' => 'Business data goes unused', 'text' => 'Valuable data exists but isn’t driving better decisions.'],
                ['heading' => 'Forecasts lack accuracy', 'text' => 'Traditional methods struggle to predict changing business conditions.'],
                ['heading' => 'Manual analysis consumes time', 'text' => 'Teams spend hours interpreting data instead of acting on it.'],
                ['heading' => 'Models fail to scale', 'text' => 'Existing solutions cannot adapt as data grows.'],
                ['heading' => 'Customer behavior remains unclear', 'text' => 'Businesses lack meaningful insights into user patterns.'],
                ['heading' => 'AI projects stall', 'text' => 'Poor implementation prevents machine learning initiatives from delivering value.'],
            ],
            'steps' => [
                ['num' => '01', 'title' => 'Assess Business Objectives', 'body' => 'We identify machine learning opportunities aligned with measurable outcomes.'],
                ['num' => '02', 'title' => 'Develop Intelligent Models', 'body' => 'Custom ML models are built using high-quality enterprise data.'],
                ['num' => '03', 'title' => 'Deploy Into Production', 'body' => 'Models integrate seamlessly with your existing systems.'],
                ['num' => '04', 'title' => 'Optimize Performance', 'body' => 'Continuous monitoring improves accuracy and long-term business value.'],
            ],
            'cta_heading' => 'Unlock Smarter Business Decisions',
            'cta_subhead' => 'Leverage machine learning solutions that turn enterprise data into measurable business outcomes.',
        ],

        'pega' => [
            'headline' => 'PEGA Solutions That Make Complex Work Flow',
            'subhead'  => 'Build intelligent workflow automation and customer engagement solutions with the PEGA platform.',
            'prob_heading' => 'Legacy Processes Reduce Operational Efficiency',
            'problems' => [
                ['heading' => 'Business processes are fragmented', 'text' => 'Multiple disconnected workflows slow daily operations.'],
                ['heading' => 'Manual approvals create delays', 'text' => 'Decision-making becomes slower and less consistent.'],
                ['heading' => 'Legacy systems limit agility', 'text' => 'Older applications struggle to support modern business needs.'],
                ['heading' => 'Customer experiences become inconsistent', 'text' => 'Disconnected systems affect service quality.'],
                ['heading' => 'Workflow visibility is limited', 'text' => 'Businesses lack real-time operational insights.'],
                ['heading' => 'Scaling operations becomes difficult', 'text' => 'Manual processes cannot support enterprise growth.'],
            ],
            'steps' => [
                ['num' => '01', 'title' => 'Analyze Existing Processes', 'body' => 'We identify workflow improvements across your organization.'],
                ['num' => '02', 'title' => 'Design PEGA Solutions', 'body' => 'Intelligent applications are tailored to your business needs.'],
                ['num' => '03', 'title' => 'Integrate Enterprise Systems', 'body' => 'PEGA connects seamlessly with legacy and cloud platforms.'],
                ['num' => '04', 'title' => 'Support Continuous Improvement', 'body' => 'Solutions evolve alongside your business requirements.'],
            ],
            'cta_heading' => 'Modernize Enterprise Workflows',
            'cta_subhead' => 'Empower your business with scalable PEGA solutions designed for long-term operational excellence.',
        ],

        'engineering-services' => [
            'headline' => 'Engineering Services Built to Move Ideas Forward',
            'subhead'  => 'Accelerate product innovation with end-to-end engineering expertise across design, validation, and development.',
            'prob_heading' => 'Engineering Challenges Delay Product Delivery',
            'problems' => [
                ['heading' => 'Product development takes too long', 'text' => 'Inefficient engineering workflows slow time-to-market.'],
                ['heading' => 'Specialized talent is difficult to find', 'text' => 'Critical engineering expertise remains in short supply.'],
                ['heading' => 'Design quality suffers', 'text' => 'Limited resources affect product performance and reliability.'],
                ['heading' => 'Validation becomes a bottleneck', 'text' => 'Testing delays impact development schedules.'],
                ['heading' => 'Cross-functional collaboration is weak', 'text' => 'Engineering teams struggle to stay aligned.'],
                ['heading' => 'Projects exceed budgets', 'text' => 'Inefficient execution increases development costs.'],
            ],
            'steps' => [
                ['num' => '01', 'title' => 'Understand Engineering Requirements', 'body' => 'We align resources with your technical objectives.'],
                ['num' => '02', 'title' => 'Provide Specialized Expertise', 'body' => 'Experienced engineers support every project phase.'],
                ['num' => '03', 'title' => 'Validate Quality', 'body' => 'Rigorous testing ensures reliable engineering outcomes.'],
                ['num' => '04', 'title' => 'Accelerate Product Delivery', 'body' => 'Efficient execution keeps projects on schedule.'],
            ],
            'cta_heading' => 'Engineer Better Products Faster',
            'cta_subhead' => 'Partner with Tecnoprism to deliver innovative engineering solutions with confidence.',
        ],

        'embedded-technologies' => [
            'headline' => 'Embedded Technologies That Power What’s Next',
            'subhead'  => 'Develop reliable embedded systems that power connected products, industrial devices, and next-generation technologies.',
            'prob_heading' => 'Embedded Systems Demand Precision and Reliability',
            'problems' => [
                ['heading' => 'Hardware and software lack alignment', 'text' => 'Integration challenges delay product development.'],
                ['heading' => 'Firmware performance is inconsistent', 'text' => 'Poor optimization affects system reliability.'],
                ['heading' => 'Testing uncovers late-stage issues', 'text' => 'Product defects increase development costs.'],
                ['heading' => 'IoT devices require stronger connectivity', 'text' => 'Connected systems struggle with scalability and security.'],
                ['heading' => 'Complex platforms increase development time', 'text' => 'Advanced embedded solutions demand specialized expertise.'],
                ['heading' => 'Product launches get delayed', 'text' => 'Engineering bottlenecks impact time-to-market.'],
            ],
            'steps' => [
                ['num' => '01', 'title' => 'Define System Architecture', 'body' => 'We design embedded solutions around your product requirements.'],
                ['num' => '02', 'title' => 'Develop Reliable Firmware', 'body' => 'Optimized software delivers stable device performance.'],
                ['num' => '03', 'title' => 'Validate Hardware Integration', 'body' => 'Thorough testing ensures dependable operation.'],
                ['num' => '04', 'title' => 'Deploy Scalable Solutions', 'body' => 'Embedded systems are built for long-term reliability and growth.'],
            ],
            'cta_heading' => 'Power Intelligent Connected Products',
            'cta_subhead' => 'Deliver robust embedded solutions that enable innovation across modern industries.',
        ],

        'digital-transformation' => [
            'headline' => 'Digital Transformation That Moves Business Forward',
            'subhead'  => 'Modernize technology, processes, and operations to build a faster, smarter, and more adaptable enterprise.',
            'prob_heading' => 'When Legacy Ways of Working Hold You Back',
            'problems' => [
                ['heading' => 'Legacy systems slow everything down', 'text' => 'Outdated technology creates bottlenecks and makes change harder than it should be.'],
                ['heading' => 'Data lives in disconnected silos', 'text' => 'Fragmented systems prevent teams from seeing the complete business picture.'],
                ['heading' => 'Manual processes drain productivity', 'text' => 'Repetitive workflows consume time that could be spent on higher-value work.'],
                ['heading' => 'Technology struggles to scale', 'text' => 'Existing infrastructure cannot keep pace with growing business demands.'],
                ['heading' => 'Customer experiences feel fragmented', 'text' => 'Disconnected digital touchpoints create inconsistent experiences across channels.'],
                ['heading' => 'Transformation lacks direction', 'text' => 'Technology investments fail to create value without a clear business-led roadmap.'],
            ],
            'steps' => [
                ['num' => '01', 'title' => 'Find What Needs to Change', 'body' => 'We assess your technology, processes, and priorities to identify high-impact opportunities.'],
                ['num' => '02', 'title' => 'Build the Transformation Roadmap', 'body' => 'We create a practical strategy aligned with your business goals and technology landscape.'],
                ['num' => '03', 'title' => 'Modernize & Integrate', 'body' => 'We connect cloud, automation, data, AI, and applications into a cohesive digital ecosystem.'],
                ['num' => '04', 'title' => 'Scale What Creates Value', 'body' => 'We continuously optimize solutions to support sustainable transformation and growth.'],
            ],
            'cta_heading' => 'Turn Transformation Into Momentum',
            'cta_subhead' => 'Build a connected, adaptable digital enterprise designed to keep moving forward.',
        ],

        'software-testing' => [
            'headline' => 'Software Testing That Catches Problems Before Users Do',
            'subhead'  => 'Build confidence into every release with rigorous testing that protects performance, reliability, and user experience.',
            'prob_heading' => 'When Small Defects Become Big Problems',
            'problems' => [
                ['heading' => 'Bugs reach production', 'text' => 'Undetected defects create costly fixes and frustrating user experiences.'],
                ['heading' => 'Releases take too long', 'text' => 'Slow testing cycles become bottlenecks between development and deployment.'],
                ['heading' => 'Updates break what already works', 'text' => 'New releases introduce regressions that compromise existing functionality.'],
                ['heading' => 'Performance fails under pressure', 'text' => 'Applications that work in testing can struggle when real-world demand increases.'],
                ['heading' => 'Testing coverage leaves blind spots', 'text' => 'Critical workflows remain vulnerable when testing is fragmented or incomplete.'],
                ['heading' => 'Quality becomes an afterthought', 'text' => 'Late-stage testing makes defects harder and more expensive to resolve.'],
            ],
            'steps' => [
                ['num' => '01', 'title' => 'Define What Cannot Fail', 'body' => 'We identify critical workflows, risks, and quality requirements before testing begins.'],
                ['num' => '02', 'title' => 'Test Across Every Layer', 'body' => 'We validate functionality, integrations, performance, and end-to-end user journeys.'],
                ['num' => '03', 'title' => 'Automate Where It Matters', 'body' => 'Repeatable testing is automated to improve coverage and accelerate release cycles.'],
                ['num' => '04', 'title' => 'Release With Confidence', 'body' => 'Clear reporting and continuous validation help teams ship reliable software faster.'],
            ],
            'cta_heading' => 'Make Every Release a Confident One',
            'cta_subhead' => 'Strengthen software quality with testing built to find problems before your customers do.',
        ],

        'data-analytics' => [
            'headline' => 'Data Analytics That Turns Complexity Into Clarity',
            'subhead'  => 'Transform scattered enterprise data into actionable insights that sharpen decisions and uncover opportunities.',
            'prob_heading' => 'When More Data Still Means Fewer Answers',
            'problems' => [
                ['heading' => 'Data sits in silos', 'text' => 'Disconnected sources prevent teams from seeing one reliable version of the truth.'],
                ['heading' => 'Reports explain the past, not the future', 'text' => 'Traditional reporting shows what happened without revealing what is likely to happen next.'],
                ['heading' => 'Decisions rely on assumptions', 'text' => 'Without timely insights, critical business choices depend more on instinct than evidence.'],
                ['heading' => 'Teams drown in dashboards', 'text' => 'More metrics create confusion when the insights that actually matter remain unclear.'],
                ['heading' => 'Opportunities stay hidden', 'text' => 'Patterns in customers, operations, and markets go unnoticed inside complex datasets.'],
                ['heading' => 'Insights arrive too late', 'text' => 'Slow analysis means decisions are made after the moment to act has already passed.'],
            ],
            'steps' => [
                ['num' => '01', 'title' => 'Connect the Right Data', 'body' => 'We bring relevant data sources together to create a reliable analytical foundation.'],
                ['num' => '02', 'title' => 'Find What Matters', 'body' => 'We uncover meaningful patterns, trends, and performance drivers hidden within your data.'],
                ['num' => '03', 'title' => 'Turn Insight Into Foresight', 'body' => 'Predictive and prescriptive analytics help anticipate outcomes and guide smarter action.'],
                ['num' => '04', 'title' => 'Put Intelligence Into Action', 'body' => 'Clear dashboards and actionable insights bring better decisions into everyday operations.'],
            ],
            'cta_heading' => 'Make Your Data Mean More',
            'cta_subhead' => 'Turn complex information into the clarity your business needs to decide what comes next.',
        ],

        'software-development-outsourcing' => [
            'headline' => 'Software Development & Outsourcing Built Around What’s Next',
            'subhead'  => 'Design, build, and scale reliable digital products with engineering expertise that grows alongside your ambitions.',
            'prob_heading' => 'When Software Becomes the Bottleneck to Growth',
            'problems' => [
                ['heading' => 'Development moves too slowly', 'text' => 'Limited capacity and complex requirements push critical products further behind schedule.'],
                ['heading' => 'The right expertise is missing', 'text' => 'Specialized technical skills can be difficult and expensive to build entirely in-house.'],
                ['heading' => 'Legacy applications hold teams back', 'text' => 'Outdated architectures make new features, integrations, and scaling increasingly difficult.'],
                ['heading' => 'Technical debt keeps growing', 'text' => 'Short-term fixes accumulate until maintaining software becomes harder than improving it.'],
                ['heading' => 'Scaling creates instability', 'text' => 'Applications built for yesterday’s demand struggle as users and workloads grow.'],
                ['heading' => 'Outsourcing creates more management', 'text' => 'Poorly aligned partners add communication gaps instead of extending your capabilities.'],
            ],
            'steps' => [
                ['num' => '01', 'title' => 'Define What You’re Building', 'body' => 'We align product goals, technical requirements, and delivery priorities before development begins.'],
                ['num' => '02', 'title' => 'Assemble the Right Expertise', 'body' => 'The right engineering capabilities are matched to your technology and delivery needs.'],
                ['num' => '03', 'title' => 'Build, Test & Integrate', 'body' => 'We develop scalable software across front-end, back-end, APIs, mobile, and enterprise integrations.'],
                ['num' => '04', 'title' => 'Scale Beyond the Launch', 'body' => 'Ongoing engineering support keeps your software reliable, adaptable, and ready to evolve.'],
            ],
            'cta_heading' => 'Build Software That Keeps Moving With You',
            'cta_subhead' => 'Extend your engineering capabilities and turn ambitious ideas into scalable digital products.',
        ],

    ];

    return $data;
}
