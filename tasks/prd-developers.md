# PRD: Developer Records for Metrics Attribution

## Introduction/Overview

Add a “Developers” feature to Kip to store canonical developer identities (name, email, GitHub username, GitLab username). This provides a reliable source of developer records for future metrics attribution.

## Goals

- Provide a CRUD interface to manage developer records.
- Require `name` and unique `email` for each developer.
- Store GitHub/GitLab usernames as optional, non-unique fields.
- Preserve user-entered casing for all fields.

## User Stories

### US-001: Persist developer data

**Description:** As a developer, I want developer records stored in the database so they persist and can be reused for metrics.

**Acceptance Criteria:**

- [ ] Create a `developers` table with `name`, `email`, `github_username`, `gitlab_username`, timestamps
- [ ] `name` is required; `email` is required and unique
- [ ] GitHub/GitLab usernames are optional and may duplicate other records
- [ ] No casing normalization is applied to stored values
- [ ] Migration runs successfully
- [ ] Add model and factory
- [ ] Related Pest tests pass

### US-002: Backend CRUD for developers

**Description:** As a developer, I want backend CRUD endpoints so the UI can manage developer records.

**Acceptance Criteria:**

- [ ] Provide endpoints for list, create, update, delete
- [ ] Form Requests validate required/unique rules
- [ ] Validation errors are user-friendly and specific
- [ ] Related Pest tests pass

### US-003: View developer list (UI)

**Description:** As a user, I want to see a list of developers so I can manage the directory.

**Acceptance Criteria:**

- [ ] List view shows name, email, GitHub, GitLab
- [ ] Empty state when no developers exist
- [ ] UI uses existing layout and styling conventions
- [ ] Related frontend linting/build passes
- [ ] Verify in browser using dev-browser skill

### US-004: Create/edit developers (UI)

**Description:** As a user, I want to create and edit developers so records stay up to date.

**Acceptance Criteria:**

- [ ] Form includes fields for name, email, GitHub, GitLab
- [ ] Inline error messages for validation failures
- [ ] Success feedback on save
- [ ] Related frontend linting/build passes
- [ ] Verify in browser using dev-browser skill

### US-005: Delete developers (UI)

**Description:** As a user, I want to delete developers so I can remove outdated records.

**Acceptance Criteria:**

- [ ] Delete action requires confirmation
- [ ] Deleted record no longer appears in list
- [ ] Related frontend linting/build passes
- [ ] Verify in browser using dev-browser skill

## Functional Requirements

- FR-1: Store `name`, `email`, `github_username`, `gitlab_username` for each developer.
- FR-2: Require `name` and `email` when creating or updating a developer.
- FR-3: Enforce unique `email` across all developers.
- FR-4: Allow duplicate GitHub/GitLab usernames across developers.
- FR-5: Preserve user-entered casing for all fields.
- FR-6: Provide CRUD operations via UI and backend routes.
- FR-7: Display a list of developers with a clear empty state.

## Non-Goals (Out of Scope)

- Importing from GitHub/GitLab or CSV
- Syncing with external APIs
- Metrics attribution logic itself
- Role/permission management tied to developers
- Soft deletes or archival workflows

## Design Considerations

- Reuse existing UI components for tables, buttons, and forms.
- Keep forms minimal and focused on the four fields.

## Technical Considerations

- Use Laravel Form Requests for validation.
- Add a unique index for `email` only.
- Do not normalize case for `email` or usernames.
- Inertia pages should live under `resources/js/Pages/Developers`.

## Success Metrics

- Adding a developer takes under 60 seconds.
- No duplicate emails in production data.
- CRUD flows pass automated tests.

## Open Questions

- None.
