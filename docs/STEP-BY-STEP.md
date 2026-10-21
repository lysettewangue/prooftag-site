# Step tracker

**Active step:** 090 — Expertise admin — **NOT STARTED**

Rules and bands: [engineering-roadmap.md](engineering-roadmap.md). Mark a row **COMPLETED** only when its deliverable and criteria are met. The next row then moves from **LOCKED** to **NOT STARTED**.

## 001–025 — Documentation and architecture

| Step | Work | Deliverable | Status |
|-----:|------|-------------|--------|
| 001 | Create the project charter | Project charter | COMPLETED |
| 002 | Freeze the initial project scope | V1 scope specification | COMPLETED |
| 003 | Freeze the confirmed product portfolio | Approved product register | COMPLETED |
| 004 | Freeze corporate contact information | Corporate contact record | COMPLETED |
| 005 | Document brand constraints | Brand constraints | COMPLETED |
| 006 | Freeze the initial information architecture | Initial sitemap | COMPLETED |
| 007 | Document legacy website sources | Legacy source register | COMPLETED |
| 008 | Inventory all legacy URLs | Legacy URL register | COMPLETED |
| 009 | Archive legacy text content | Legacy content archive | COMPLETED |
| 010 | Archive legacy media | Legacy media archive | COMPLETED |
| 011 | Create the content audit register | Content audit register | COMPLETED |
| 012 | Classify all legacy content | Classified inventory | COMPLETED |
| 013 | Reconcile duplicate content | Reconciliation register | COMPLETED |
| 014 | Identify unresolved content conflicts | Verification register | COMPLETED |
| 015 | Finalise product content baselines | Product content specification | COMPLETED |
| 016 | Define public user groups | Audience matrix | COMPLETED |
| 017 | Define public functional requirements | Public requirements | COMPLETED |
| 018 | Define admin functional requirements | Administration requirements | COMPLETED |
| 019 | Define non-functional requirements | Non-functional requirements | COMPLETED |
| 020 | Finalise the complete sitemap | Final sitemap | COMPLETED |
| 021 | Define content types | Content type specification | COMPLETED |
| 022 | Define relationships between content types | Relationship map | COMPLETED |
| 023 | Confirm system architecture | Architecture specification | COMPLETED |
| 024 | Define backend layers | Layer specification | COMPLETED |
| 025 | Define environment architecture | Environment architecture | COMPLETED |

## 026–038 — Local foundation

| Step | Work | Deliverable | Status |
|-----:|------|-------------|--------|
| 026 | Prepare the local development machine | PHP 8.4, MySQL, Node, Git verified | COMPLETED |
| 027 | Create the private Git repository | Repository with `main` | COMPLETED |
| 028 | Define source control rules | Ignore rules and commit conventions | COMPLETED |
| 029 | Create the Laravel 13 project | Clean app starts locally | COMPLETED |
| 030 | Configure core local settings | Name, timezone, locales, storage, cache, logs | COMPLETED |
| 031 | Configure the local MySQL database | Connectivity verified | COMPLETED |
| 032 | Configure local email testing | Mail cannot reach real recipients | COMPLETED |
| 033 | Install the Filament foundation | Admin login only | COMPLETED |
| 034 | Establish localisation | French default, English secondary | COMPLETED |
| 035 | Define administrative roles | Eight roles recorded | COMPLETED |
| 036 | Implement the permission foundation | Enforced on the server | COMPLETED |
| 037 | Establish audit logging | Administrative actions recorded | COMPLETED |
| 038 | Establish quality tooling | Pint, Larastan, Pest, frontend lint pass | COMPLETED |

## 039–069 — Database

| Step | Work | Deliverable | Status |
|-----:|------|-------------|--------|
| 039 | Begin conceptual database design | Principal entities listed | COMPLETED |
| 040 | Define corporate entities | Settings, pages, team, partners | COMPLETED |
| 041 | Define solution entities | Solutions, translations, relationships | COMPLETED |
| 042 | Define expertise entities | Expertise and translations | COMPLETED |
| 043 | Define technical inspection entities | Content, 12 points, equipment | COMPLETED |
| 044 | Define centre directory entities | Regions, centres, verification | COMPLETED |
| 045 | Define editorial entities | Articles, categories, publication states | COMPLETED |
| 046 | Define resource entities | Resources and current/historical status | COMPLETED |
| 047 | Define media entities | Path, MIME, rights, alt text | COMPLETED |
| 048 | Define contact entities | Requests and processing statuses | COMPLETED |
| 049 | Define SEO and redirect entities | Metadata and redirects | COMPLETED |
| 050 | Define the translation strategy | Parent records plus FR/EN translations | COMPLETED |
| 051 | Produce the entity relationship diagram | ERD with cardinalities | COMPLETED |
| 052 | Produce the data dictionary | Field, type, purpose, validation | COMPLETED |
| 053 | Define database constraints | Keys, uniqueness, nullability | COMPLETED |
| 054 | Define database indexes | Indexes for real queries | COMPLETED |
| 055 | Review and approve the database design | **Gate.** No migrations before approval | COMPLETED |
| 056 | Implement reference table migrations | Regions and controlled statuses | COMPLETED |
| 057 | Implement system and identity tables | Users, permissions, settings | COMPLETED |
| 058 | Implement corporate tables | Corporate schema | COMPLETED |
| 059 | Implement solution tables | Solution schema | COMPLETED |
| 060 | Implement expertise tables | Expertise schema | COMPLETED |
| 061 | Implement technical inspection tables | Inspection schema | COMPLETED |
| 062 | Implement directory tables | Directory schema | COMPLETED |
| 063 | Implement editorial tables | Editorial schema | COMPLETED |
| 064 | Implement resource tables | Resource schema | COMPLETED |
| 065 | Implement media tables | Media schema | COMPLETED |
| 066 | Implement contact tables | Contact schema | COMPLETED |
| 067 | Implement SEO and redirect tables | SEO schema | COMPLETED |
| 068 | Seed controlled reference data | Verified data only | COMPLETED |
| 069 | Test the database structure | **Gate.** Business logic waits on these tests | COMPLETED |

## 070–087 — Backend

| Step | Work | Deliverable | Status |
|-----:|------|-------------|--------|
| 070 | Implement domain models | Eloquent models | COMPLETED |
| 071 | Implement domain enums | Publication, centre, solution, enquiry | COMPLETED |
| 072 | Implement domain rules | Public visibility and status rules | COMPLETED |
| 073 | Define application use cases | Named operations | COMPLETED |
| 074 | Implement solution actions | Create and update solution | COMPLETED |
| 075 | Implement editorial actions | Draft through archive | COMPLETED |
| 076 | Implement centre actions | Add, verify, unavailable, archive | COMPLETED |
| 077 | Implement resource actions | Resource operations | COMPLETED |
| 078 | Implement contact processing | Enquiry workflow | COMPLETED |
| 079 | Implement authorization policies | Privileged operations checked server-side | COMPLETED |
| 080 | Implement request validation | Validation per use case | COMPLETED |
| 081 | Implement query services | Articles, centres, resources, related content | COMPLETED |
| 082 | Implement the media backend | Validate, derivatives, safe removal | COMPLETED |
| 083 | Implement the email service | Contact and account mail | COMPLETED |
| 084 | Implement search | MySQL search | COMPLETED |
| 085 | Implement cache | Stable content and invalidation | COMPLETED |
| 086 | Implement scheduling | Short tasks only | COMPLETED |
| 087 | Implement SEO services | Metadata, canonical, hreflang, sitemap, redirects | COMPLETED |

## 088–113 — Administration and model freeze

| Step | Work | Deliverable | Status |
|-----:|------|-------------|--------|
| 088 | Global settings admin | Company, contact, logo, external URLs | COMPLETED |
| 089 | Solutions admin | Five confirmed solutions | COMPLETED |
| 090 | Expertise admin | Expertise module | NOT STARTED |
| 091 | Technical inspection admin | Inspection module | LOCKED |
| 092 | Security points admin | Twelve points, reorderable | LOCKED |
| 093 | Equipment admin | Current and legacy equipment | LOCKED |
| 094 | Centre directory admin | Centres module | LOCKED |
| 095 | Team admin | Team module | LOCKED |
| 096 | Partners admin | Partners module | LOCKED |
| 097 | News and articles admin | Full editorial workflow | LOCKED |
| 098 | Resource library admin | Documents module | LOCKED |
| 099 | Careers admin | Careers module | LOCKED |
| 100 | Media library admin | Media module | LOCKED |
| 101 | Contact request admin | Enquiry module | LOCKED |
| 102 | SEO and redirect admin | SEO module | LOCKED |
| 103 | Admin user management | Users and roles | LOCKED |
| 104 | Admin audit interface | Audit view | LOCKED |
| 105 | Controlled content blocks | Approved blocks; no brand editing | LOCKED |
| 106 | Test admin permissions | **Gate.** Each role tested | LOCKED |
| 107 | Enter representative products | Five solutions in the CMS | LOCKED |
| 108 | Enter representative technical content | Points, equipment, document security | LOCKED |
| 109 | Enter representative centres | Small verified subset | LOCKED |
| 110 | Create sample news and articles | Realistic records | LOCKED |
| 111 | Test French and English management | Slugs, publication, missing translations | LOCKED |
| 112 | Review the model against real data | Gaps recorded | LOCKED |
| 113 | Freeze the V1 data model | **Gate.** Further schema change is exceptional | LOCKED |

## 114–150 — Design and public site

| Step | Work | Deliverable | Status |
|-----:|------|-------------|--------|
| 114 | Prepare the UI/UX brief | Audiences, templates, accessibility | LOCKED |
| 115 | Extract official brand tokens | Royal Blue and supporting shades | LOCKED |
| 116 | Create low-fidelity wireframes | Key templates | LOCKED |
| 117 | Review information hierarchy | Hierarchy supports real content | LOCKED |
| 118 | Create high-fidelity designs | Visual designs | LOCKED |
| 119 | Create responsive designs | Desktop, tablet, mobile | LOCKED |
| 120 | Define the design system | Type, colour, components, states | LOCKED |
| 121 | Review design accessibility | Contrast, focus, labels | LOCKED |
| 122 | Approve UI/UX | **Gate.** Frontend code starts after this | LOCKED |
| 123 | Configure Tailwind tokens | Tokens in code | LOCKED |
| 124 | Implement the public layout | Global shell | LOCKED |
| 125 | Implement navigation | No Home item; logo opens the current language | LOCKED |
| 126 | Implement the footer | Values from global settings | LOCKED |
| 127 | Implement shared components | Buttons, cards, forms, navigation pieces | LOCKED |
| 128 | Integrate Lucide | Public icon set | LOCKED |
| 129 | Implement the JavaScript foundation | Vanilla JS, then Alpine | LOCKED |
| 130 | Implement the homepage | Bound to CMS data | LOCKED |
| 131 | Implement About Us | About templates | LOCKED |
| 132 | Implement the solutions index | Solutions listing | LOCKED |
| 133 | Implement the product detail template | Checked against all five products | LOCKED |
| 134 | Implement expertise | Index and detail | LOCKED |
| 135 | Implement technical inspection | Inspection section | LOCKED |
| 136 | Implement the 12 security points | Public presentation | LOCKED |
| 137 | Implement equipment | Equipment presentation | LOCKED |
| 138 | Implement the centre directory | Search, filters, pagination | LOCKED |
| 139 | Implement the centre detail page | Centre page | LOCKED |
| 140 | Implement the resource library | Resources | LOCKED |
| 141 | Implement the news listing | Listing | LOCKED |
| 142 | Implement the article page | Article detail | LOCKED |
| 143 | Implement careers | Careers | LOCKED |
| 144 | Implement contact | Contact page | LOCKED |
| 145 | Implement legal pages | Legal templates | LOCKED |
| 146 | Implement public search | Search | LOCKED |
| 147 | Implement language switching | Equivalent translated content | LOCKED |
| 148 | Implement SEO output | Title, canonical, hreflang, Open Graph | LOCKED |
| 149 | Implement sitemap and robots | Sitemap and robots | LOCKED |
| 150 | Review the public frontend | **Gate.** Templates work on CMS data | LOCKED |

## 151–186 — Migration, quality, release candidate

| Step | Work | Deliverable | Status |
|-----:|------|-------------|--------|
| 151 | Migrate corporate content | Approved About content | LOCKED |
| 152 | Migrate product content | Five products | LOCKED |
| 153 | Migrate expertise content | Expertise | LOCKED |
| 154 | Migrate technical inspection content | Inspection knowledge | LOCKED |
| 155 | Migrate equipment content | Equipment | LOCKED |
| 156 | Migrate the centre directory | Verification status preserved | LOCKED |
| 157 | Migrate resource documents | Current and historical separated | LOCKED |
| 158 | Migrate retained news and articles | Selected editorial content | LOCKED |
| 159 | Migrate media | Optimised, classified files | LOCKED |
| 160 | Verify migrated content | Compared with the audit | LOCKED |
| 161 | Complete the French editorial review | French approved | LOCKED |
| 162 | Complete the English translation | English content | LOCKED |
| 163 | Review technical English | Terminology checked | LOCKED |
| 164 | Verify French and English routes | Route check | LOCKED |
| 165 | Finalise the legacy redirect register | Deliberate URL map | LOCKED |
| 166 | Decide DITROS-CT URL behaviour | No implied Certidocs relationship | LOCKED |
| 167 | Implement redirects locally | Redirects working locally | LOCKED |
| 168 | Create unit tests | Isolated business rules | LOCKED |
| 169 | Create feature tests | Routes, publishing, centres, contact | LOCKED |
| 170 | Create authorization tests | Allowed and denied | LOCKED |
| 171 | Create database integration tests | Constraints and relations | LOCKED |
| 172 | Create email and filesystem tests | Mail and files | LOCKED |
| 173 | Create browser tests | Critical journeys | LOCKED |
| 174 | Run static analysis | Significant issues resolved | LOCKED |
| 175 | Run format checks | Pint clean | LOCKED |
| 176 | Configure continuous integration | Dependencies, analysis, tests, production build | LOCKED |
| 177 | Perform manual functional QA | Functional review | LOCKED |
| 178 | Perform admin QA | Every role | LOCKED |
| 179 | Perform browser compatibility QA | Target browsers | LOCKED |
| 180 | Perform responsive QA | Desktop, tablet, mobile | LOCKED |
| 181 | Perform accessibility QA | Accessibility review | LOCKED |
| 182 | Perform the security review | Auth, uploads, CSRF, exposure | LOCKED |
| 183 | Review backend performance | Queries, pagination, indexes, cache | LOCKED |
| 184 | Review frontend performance | Images, CSS, JS, fonts, maps | LOCKED |
| 185 | Apply production optimisations locally | Optimised run still works | LOCKED |
| 186 | Create the local release candidate | **Gate.** No LWS work before this | LOCKED |

## 187–233 — Staging and production

| Step | Work | Deliverable | Status |
|-----:|------|-------------|--------|
| 187 | Audit the LWS Starter account | PHP, MySQL, cron, SSL, mail, docroot | LOCKED |
| 188 | Document LWS constraints | Observed account limits | LOCKED |
| 189 | Create the staging database | Separate database | LOCKED |
| 190 | Create the restricted staging environment | Protected and noindex | LOCKED |
| 191 | Deploy the release candidate to staging | Staging deploy | LOCKED |
| 192 | Configure the staging environment | URL, mail, storage, production-like settings | LOCKED |
| 193 | Verify the public directory | Project root not web-accessible | LOCKED |
| 194 | Verify file permissions | Laravel writable paths | LOCKED |
| 195 | Run staging migrations | Migrations applied | LOCKED |
| 196 | Deploy staging content | Content loaded | LOCKED |
| 197 | Test staging admin | Admin works | LOCKED |
| 198 | Test the staging public site | Public site works | LOCKED |
| 199 | Test staging uploads | Media uploads | LOCKED |
| 200 | Test staging mail | SMTP | LOCKED |
| 201 | Test the LWS scheduler | Scheduled publication | LOCKED |
| 202 | Test staging redirects | Redirects | LOCKED |
| 203 | Test staging performance | Measured on LWS | LOCKED |
| 204 | Test staging security | Security check | LOCKED |
| 205 | Accept staging | **Gate.** Production stays closed | LOCKED |
| 206 | Freeze the production release | One tested version | LOCKED |
| 207 | Reconcile late legacy changes | Delta captured | LOCKED |
| 208 | Prepare the production content set | Final dataset | LOCKED |
| 209 | Build production frontend assets | Compiled assets | LOCKED |
| 210 | Prepare production configuration | Debug off, HTTPS, production mail | LOCKED |
| 211 | Back up the legacy website | Restorable backup | LOCKED |
| 212 | Write the rollback plan | Exact rollback steps | LOCKED |
| 213 | Prepare the production database | Database ready | LOCKED |
| 214 | Deploy the approved release | Production deploy | LOCKED |
| 215 | Apply production migrations | Migrations applied | LOCKED |
| 216 | Load production content | Approved content | LOCKED |
| 217 | Apply production optimisation | Caches built | LOCKED |
| 218 | Activate legacy redirects | Redirects live | LOCKED |
| 219 | Cut over the domain | Mail and unrelated DNS preserved | LOCKED |
| 220 | Verify HTTPS | No mixed content | LOCKED |
| 221 | Smoke-test the homepage | Homepage | LOCKED |
| 222 | Test logo-to-home in French and English | Logo behaviour | LOCKED |
| 223 | Test main navigation | Navigation | LOCKED |
| 224 | Verify the five solutions | Products | LOCKED |
| 225 | Verify news and articles | Editorial | LOCKED |
| 226 | Verify the centre directory | Centres | LOCKED |
| 227 | Verify resources | Resources | LOCKED |
| 228 | Verify contact and email | Forms and delivery | LOCKED |
| 229 | Verify production admin | Admin | LOCKED |
| 230 | Verify scheduled publishing | Scheduler | LOCKED |
| 231 | Verify production redirects | Redirects | LOCKED |
| 232 | Verify production SEO | Sitemap, robots, canonical, hreflang | LOCKED |
| 233 | Submit the sitemap | Search consoles | LOCKED |

## 234–250 — Operations

| Step | Work | Deliverable | Status |
|-----:|------|-------------|--------|
| 234 | Enable production monitoring | Uptime, errors, 404s, storage | LOCKED |
| 235 | Monitor launch errors | Launch review | LOCKED |
| 236 | Review missed redirects | Important 404s corrected | LOCKED |
| 237 | Review email delivery | Reliable contact mail | LOCKED |
| 238 | Review live performance | Traffic review | LOCKED |
| 239 | Write technical documentation | Architecture through recovery | LOCKED |
| 240 | Write the administrator manual | CMS operating guide | LOCKED |
| 241 | Write content governance | Writer through publisher | LOCKED |
| 242 | Write the backup procedure | Source, database, media, configuration | LOCKED |
| 243 | Test a full restoration | Restore demonstrated | LOCKED |
| 244 | Hand over the application | Access, docs, responsibilities | LOCKED |
| 245 | Begin routine maintenance | Update and security review | LOCKED |
| 246 | Review content accuracy | Team, centres, products, contact | LOCKED |
| 247 | Review hosting quotas | Storage, database, bandwidth | LOCKED |
| 248 | Review infrastructure needs | VPS only with a real trigger | LOCKED |
| 249 | Plan framework upgrades | Before end of security support | LOCKED |
| 250 | Continuous improvement | Requirement → design → test → staging → production | LOCKED |
