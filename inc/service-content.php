<?php
/**
 * Content for the 3 service pages, keyed by page slug — mirrors the shape
 * tp_get_section() uses for the homepage. Transitional: this is a plain
 * PHP array today; once ACF is wired up (see project notes), each page's
 * fields will move to post meta and this file becomes just the fallback.
 *
 * All copy below is taken directly from the design handoff files, not
 * placeholder text.
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

    ];

    return $data;
}
