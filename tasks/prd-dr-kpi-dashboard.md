# PRD: Disaster Recovery KPI Dashboard

## Introduction

A personal dashboard for tracking Disaster Recovery (DR) test results over time, focused on two key metrics: Recovery Time Objective (RTO) and Recovery Point Objective (RPO). Users can manually enter data from each DR test, view trends via interactive line charts, and monitor progress toward target thresholds (< 1 hour for both metrics) through dedicated widgets.

## Goals

- Enable manual entry of DR test results with detailed phase breakdowns
- Visualize RTO and RPO trends over time via interactive charts
- Display current RTO/RPO values with progress indicators toward target thresholds
- Allow user-configurable target thresholds per KPI
- Provide a clean, single-user dashboard experience

## User Stories

### US-001: Create DR Test database schema

**Description:** As a developer, I need to store DR test data so it persists across sessions.

**Acceptance Criteria:**

- [ ] Create `dr_tests` table with columns: id, test_date, rto_minutes, rpo_minutes, notes (nullable), created_at, updated_at
- [ ] Create `dr_test_phases` table with columns: id, dr_test_id (foreign key), title, started_at, finished_at, duration_minutes (calculated/stored)
- [ ] Generate and run migrations successfully
- [ ] Create DrTest and DrTestPhase Eloquent models with relationships
- [ ] Create factories for both models
- [ ] Typecheck passes

### US-002: Create KPI targets database schema

**Description:** As a developer, I need to store user-configurable KPI target thresholds.

**Acceptance Criteria:**

- [ ] Create `kpi_targets` table with columns: id, kpi_type (enum: 'rto', 'rpo'), target_minutes, created_at, updated_at
- [ ] Generate and run migration successfully
- [ ] Seed default targets: RTO = 60 minutes, RPO = 60 minutes
- [ ] Create KpiTarget Eloquent model
- [ ] Typecheck passes

### US-003: Build DR test entry form

**Description:** As a user, I want to manually enter DR test results so I can track my disaster recovery performance.

**Acceptance Criteria:**

- [ ] Form includes: test date picker, notes text area
- [ ] Dynamic phase section: add/remove phases with title, start time, finish time inputs
- [ ] Duration auto-calculates from start/finish times and displays in the form
- [ ] RTO and RPO fields for entering the final metrics (in minutes)
- [ ] Form validation: test date required, at least one phase required, RTO/RPO required and must be positive numbers
- [ ] Successful submission saves to database and redirects to dashboard
- [ ] Typecheck passes
- [ ] Verify in browser using dev-browser skill

### US-004: Build RTO/RPO trend chart

**Description:** As a user, I want to see my RTO and RPO values graphed over time so I can visualize improvement trends.

**Acceptance Criteria:**

- [ ] Install shadcn chart component (uses Recharts under the hood)
- [ ] Line chart showing RTO and RPO as separate lines over time (x-axis: test date, y-axis: minutes)
- [ ] Interactive tooltips showing exact values on hover
- [ ] Target threshold displayed as horizontal reference line (dashed)
- [ ] Chart responsive to container width
- [ ] Empty state when no test data exists
- [ ] Typecheck passes
- [ ] Verify in browser using dev-browser skill

### US-005: Build RTO progress widget

**Description:** As a user, I want to see my current RTO value and progress toward the target so I know how much improvement is needed.

**Acceptance Criteria:**

- [ ] Widget displays latest RTO value prominently
- [ ] Shows target threshold value
- [ ] Visual progress indicator (radial chart or progress bar) showing percentage toward goal
- [ ] Color coding: green when at/below target, yellow when within 50%, red when over 50% above target
- [ ] Shows "Target achieved!" when RTO ≤ target
- [ ] Graceful empty state when no data
- [ ] Typecheck passes
- [ ] Verify in browser using dev-browser skill

### US-006: Build RPO progress widget

**Description:** As a user, I want to see my current RPO value and progress toward the target so I know how much improvement is needed.

**Acceptance Criteria:**

- [ ] Widget displays latest RPO value prominently
- [ ] Shows target threshold value
- [ ] Visual progress indicator (radial chart or progress bar) showing percentage toward goal
- [ ] Color coding: green when at/below target, yellow when within 50%, red when over 50% above target
- [ ] Shows "Target achieved!" when RPO ≤ target
- [ ] Graceful empty state when no data
- [ ] Typecheck passes
- [ ] Verify in browser using dev-browser skill

### US-007: Build KPI target configuration UI

**Description:** As a user, I want to configure my target thresholds for RTO and RPO so I can set goals appropriate for my organization.

**Acceptance Criteria:**

- [ ] Settings page or modal with RTO and RPO target inputs (in minutes)
- [ ] Current values pre-populated in form
- [ ] Validation: targets must be positive numbers
- [ ] Save updates targets in database
- [ ] Dashboard widgets and charts update to reflect new targets
- [ ] Typecheck passes
- [ ] Verify in browser using dev-browser skill

### US-008: Build dashboard page layout

**Description:** As a user, I want a dashboard that displays all DR KPI information at a glance.

**Acceptance Criteria:**

- [ ] Dashboard route accessible at `/dashboard` or `/`
- [ ] Layout includes: RTO widget, RPO widget, trend chart, link/button to add new test
- [ ] Responsive grid layout (widgets side-by-side on desktop, stacked on mobile)
- [ ] Link to settings/configuration for targets
- [ ] Typecheck passes
- [ ] Verify in browser using dev-browser skill

### US-009: List and view DR test history

**Description:** As a user, I want to view my past DR test entries so I can review details and track history.

**Acceptance Criteria:**

- [ ] Table or list view of all DR tests, sorted by date (newest first)
- [ ] Each row shows: test date, RTO, RPO, number of phases
- [ ] Clicking a row expands or navigates to detail view showing all phases
- [ ] Phase details show: title, start time, finish time, calculated duration
- [ ] Typecheck passes
- [ ] Verify in browser using dev-browser skill

### US-010: Edit and delete DR test entries

**Description:** As a user, I want to edit or delete DR test entries so I can correct mistakes.

**Acceptance Criteria:**

- [ ] Edit button on test detail view opens pre-populated form
- [ ] Delete button with confirmation dialog
- [ ] Successful edit/delete redirects to history or dashboard
- [ ] Charts and widgets update after edit/delete
- [ ] Typecheck passes
- [ ] Verify in browser using dev-browser skill

## Functional Requirements

- FR-1: Store DR test results with test date, RTO (minutes), RPO (minutes), and optional notes
- FR-2: Store multiple phases per test with title, start time, finish time, and auto-calculated duration
- FR-3: Store user-configurable KPI target thresholds for RTO and RPO
- FR-4: Display interactive line chart of RTO/RPO values over time using shadcn charts (Recharts)
- FR-5: Display target threshold as reference line on trend chart
- FR-6: Display RTO widget showing latest value, target, and visual progress indicator
- FR-7: Display RPO widget showing latest value, target, and visual progress indicator
- FR-8: Color-code progress widgets based on proximity to target (green/yellow/red)
- FR-9: Provide form for manual DR test entry with dynamic phase management
- FR-10: Provide settings interface for configuring RTO/RPO target thresholds
- FR-11: List all DR tests with ability to view details, edit, and delete
- FR-12: Auto-calculate phase duration from start/finish times

## Non-Goals

- No multi-user/team functionality (single user only)
- No automated data import from external systems
- No notifications or alerts when targets are met/missed
- No PDF/export functionality
- No authentication (assume single-user local setup or existing auth)
- No comparison between different time periods
- No additional KPI types beyond RTO/RPO in initial implementation

## Design Considerations

- Use shadcn/ui components for consistent styling
- Use shadcn charts (built on Recharts) for all data visualization
- Consider `chart-line-interactive` block for trend chart
- Consider `chart-radial-text` block for progress widgets
- Dashboard should follow existing app layout/navigation patterns
- Mobile-responsive design required

## Technical Considerations

- Charts: Install `@shadcn/chart` component which includes Recharts dependency
- Inertia.js for page rendering and form handling
- Use Wayfinder for type-safe route generation
- Phase duration calculation: can be computed on save or as an accessor on the model
- Consider using deferred props for chart data if dataset grows large
- Form validation via Laravel Form Request classes

## Success Metrics

- User can enter a new DR test in under 2 minutes
- Dashboard loads with charts and widgets in under 1 second
- Progress toward target is immediately visible upon viewing dashboard
- All test history is accessible and searchable

## Open Questions

- Should phase times be stored as full datetime or just time-of-day?
- Should we track which system/application the DR test was for (future extensibility)?
- Should the trend chart support date range filtering?
