# Maharaj Bachat Admin Panel Implementation Plan

## Safety and architecture decisions

- Preserve the public website and member dashboard; staff administration lives under `/admin`.
- Public registration must never grant staff privileges. Initial administrator provisioning is through `php artisan admin:create`, with a hidden password prompt.
- Do not show placeholder financial totals. Dashboard figures will be introduced only when backed by verified records and authorized workflows.
- Do not invent cooperative interest rates, eligibility requirements, fee schedules, approval thresholds, or accounting/rounding policy. Confirm those policies before enabling financial posting.
- Every phase adds authorization and automated coverage at its request boundary. Posted financial records will use append-only reversals/adjustments, transaction boundaries, and unique references.

## Phases

1. **Admin foundation** — separate staff-only routes and authentication boundary, secure first-admin provisioning, responsive green/yellow shell, bilingual admin labels, and access tests. **Completed.**
2. **Staff roles and membership** — configurable roles/permissions, member records and profiles, searchable member lists, and auditable membership application review/approval. **Implemented; run the migrations and assign permissions to staff roles before using the workflow.**
3. **Savings management** — approved savings products and accounts, statements, and controlled, idempotent deposit/withdrawal/reversal workflows.
4. **Loan management** — approved loan products and application review, separately authorized approval/disbursement, installment schedules, repayment posting, and statements.
5. **Notices and website content** — authorized bilingual notice publishing, website content, safe document handling, and contact inquiry management.
6. **Reports and operational security** — permission-scoped reports/exports, notifications, audit history, final authorization/security tests, and deployment/backup guidance.

## Phase 2 status

- Public registration creates a pending membership application; it does not create an active member or grant staff access.
- Staff can search applications, request supporting documents, start review, approve or reject applications, and inspect application history. Uploaded identity documents are stored privately and require staff permission to download.
- Application approval creates an active member record. Staff can search member records, update contact details, suspend or reactivate members with a recorded reason, and view membership history.
- Role permissions and staff-role assignment are available to authorized staff managers. Only a provisioned super administrator can grant administration access or staff-management permissions.
- Member dashboard routes require an active member record.
- Feature tests cover registration, approval/rejection, private documents, member access, staff permissions, contact record maintenance, and authorization.
- **Production check:** the current internal member-number format (`MBR-000001`) is provisional; confirm it matches the cooperative's official numbering policy before issuing production member numbers.

## Contact inquiry workflow

- The public contact page provides a message form beside the office information and displays the Kawasoti -2 BP Chowk map below both cards.
- Valid submissions are rate-limited and saved as private-to-staff inquiries. Staff with the `contact_inquiries.manage` permission can search, view, and mark inquiries reviewed.
- Messages are stored for staff follow-up; this workflow does not send email notifications.

## Phase 1 status

- Existing Laravel 13.35 / PHP 8.5 project inspected; current database is SQLite and contains users, settings, and notices only.
- Admin access is isolated from member access; regular users are not staff by default.
- Dashboard intentionally reports no financial totals until authoritative data and workflows exist.
- Guest/member/admin access, public registration privilege protection, and locale switching are covered by six passing feature tests.
- Phase 1 foundation is extended by the Phase 2 workflows above. Policy-dependent finance work remains unimplemented and will not be enabled with invented values.

## Phase 1 setup

```sh
php artisan migrate
php artisan admin:create
```

The account-creation command prompts interactively and does not echo the password. Public registration cannot grant administrative access.
