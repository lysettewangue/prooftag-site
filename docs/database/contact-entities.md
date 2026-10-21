# Contact entities

| | |
|--|--|
| **Step** | 048 — Define contact entities |
| **Parent** | [principal-entities.md](principal-entities.md) (039) · [contact-master-record.md](../charter/contact-master-record.md) (004) |
| **Next** | 089 — Solutions admin |
| **Tracker** | [STEP-BY-STEP.md](../STEP-BY-STEP.md) |
| **Version** | 1.0 |
| **Date** | 2026-10-21 |
| **Status** | Requests and processing statuses specified |

This is the step 048 deliverable. Domain: `App\Domain\Communication`. The contact **page shell** is Page `contact` (040). Address/phone/email come from **Settings**, not from this table. Not translatable. **No migrations before gate 055.**

Honeypot (Spatie) + rate limit. Mail to `info@prooftagcatis.com`. Do not store passwords (there are none). Do not store card data.

## 1. ContactRequest

| Attribute | Rule |
|-----------|------|
| `name` | Required. |
| `email` | Required, valid email. |
| `phone` | Optional. |
| `subject` | Optional short text. |
| `message` | Required. |
| `locale` | `fr` or `en` — the form locale. |
| `status` | `new` → `in_progress` → `responded` → `closed`; or `spam`. |
| `ip` / `user_agent` | For abuse review. Not shown publicly. |
| `notified_at` | When inbound mail was dispatched. Spam must **not** send mail. |

No public self-registration. Support Agent owns the inbox; Communications Editor may also (035 matrix). No **publish** on this type.

## 2. Relationships

None to Product or Centre. Page `contact` has no FK here.

## 3. Acceptance

| Role | Decision | Date |
|------|----------|------|
| Product owner | Contact entities specified (step 048 executed) | 2026-10-21 |
