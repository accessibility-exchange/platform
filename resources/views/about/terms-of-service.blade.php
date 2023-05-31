<x-app-layout page-width="wide">
    <x-slot name="title">{{ __('Terms of Service') }}</x-slot>
    <x-slot name="header">
        <ol class="breadcrumbs" role="list">
            <li><a href="{{ localized_route('welcome') }}">{{ __('About the Accessibility Exchange') }}</a></li>
        </ol>
        <h1>
            {{ __('Terms of Service') }}
        </h1>
    </x-slot>

    <div class="counter stack">
        <p><strong>{{ __('Last updated: :date', ['date' => $modifiedAt->isoFormat('LL')]) }}</strong></p>

        <p>{{ __('Please read these terms of service carefully.') }}</p>

        {{ safe_markdown(
            'Institute for Research and Development on Inclusion and Society (“**IRIS**”, “**we**”, “**us**” or “**our**”) invites you (“**you**” or “**your**”) to join The Accessibility Exchange, a platform enabling people with disabilities, Deaf persons, and their supporters and people and organizations with expertise in supporting these communities to connect with one another and with federally regulated organizations (“**FROs**”) and other organizations (together with FROs, the “**Project Proponents**”), for the purpose of developing accessibility plans or other relevant services (the “**Service**”), which you can access at **[<:url>]** or on our mobile applications (the “**Platform**”). These terms of service and the IRIS Privacy Policy (together, the “**Terms**”) govern your use of the Platform and represent a legally binding agreement between you and IRIS.',
            ['url' => $appURL],
        ) }}

        <p>{{ __('By clicking to accept the Terms or otherwise using the Platform you agree to be bound by these Terms.') }}
        </p>

        <h2 class="counter__item">{{ __('Definitions') }}</h2>
        <ol class="stack" type="a">
            <li>{{ safe_inlineMarkdown('“**Accessibility Consultant**” means an individual or organization helping Project Proponents design, facilitate and complete their Projects.') }}
            </li>
            <li>{{ safe_inlineMarkdown('“**Community Connector**” means an individual or organization helping connect communities to Project Proponents seeking knowledge about how to connect with and/or support the community or Consultation Participants.') }}
            </li>
            <li>{{ safe_inlineMarkdown('“**Consultation Participant**” means an individual or organization participating in consultations hosted by Project Proponents.') }}
            </li>
            <li>{{ safe_inlineMarkdown('“**Consultants**” means an Accessibility Consultant, Community Connector, or Consultation Participants, as the context may require.') }}
            </li>
            <li>{{ safe_inlineMarkdown('“**Content**” means documents, including agreements or memorandums of understanding, code, links, video, images, information, data, text, software, music, sound, photographs, images, graphics, messages, financial information, postings, files, identification, video, key word data and other advertising content, and other information or materials.') }}
            </li>
            <li>{{ safe_inlineMarkdown('“**FRO**” has the meaning set out in the opening paragraph of these Terms.') }}
            </li>
            <li>{{ safe_inlineMarkdown('“**Indemnitees**” has the meaning set out in Section 12(a) of these Terms.') }}
            </li>
            <li>{{ safe_inlineMarkdown('“**IRIS Trademarks**” has the meaning set out in Section 9(b) of these Terms.') }}
            </li>
            <li>{{ safe_inlineMarkdown('“**Project**” means any engagement to develop an accessibility plan or to undertake other activities to improve accessibility, between a Consultant and a Project Proponent pursuant to a Project Agreement.') }}
            </li>
            <li>{{ safe_inlineMarkdown('“**Project Agreement**” has the meaning set out in Section 2(b) of these Terms.') }}
            </li>
            <li>{{ safe_inlineMarkdown('“**Registration Information**” has the meaning set out in Section 3(a) of these Terms.') }}
            </li>
            <li>{{ safe_inlineMarkdown('“**Service Content**” has the meaning set out in Section 9(a) of these Terms.') }}
            </li>
            <li>{{ safe_inlineMarkdown('“**Services**” has the meaning set out in the opening paragraph of these Terms.') }}
            </li>
            <li>{{ safe_inlineMarkdown('“**Submissions**” has the meaning set out in Section 10(c) of these Terms.') }}
            </li>
            <li>{{ safe_inlineMarkdown('“**Third Party Content**” has the meaning set out in Section 8(a) of these Terms.') }}
            </li>
            <li>{{ safe_inlineMarkdown('“**Third Party Provider**” has the meaning set out in Section 8(a) of these Terms.') }}
            </li>
            <li>{{ safe_inlineMarkdown('“**Third Party Service**” has the meaning set out in Section 8(b) of these Terms.') }}
            </li>
            <li>{{ safe_inlineMarkdown('“**Third Party Terms**” has the meaning set out in Section 8(b) of these Terms.') }}
            </li>
            <li>{{ safe_inlineMarkdown('“**Underaged Person**” has the meaning set out in Section 3(a) of these Terms.') }}
            </li>
            <li>{{ safe_inlineMarkdown('“**Upload**” means to upload, post or publish on the Platform.') }}</li>
            <li>{{ safe_inlineMarkdown('“**User**” or “**you**” means a Project Proponent or Consultant, as the circumstances may indicate or require. ') }}
            </li>
            <li>{{ safe_inlineMarkdown('“**User Content**” has the meaning set out in Section 7(a) of these Terms.') }}
            </li>
        </ol>

        <h2 class="counter__item">
            {{ __('IRIS IS NOT A PARTY TO ANY PROJECT OR PROJECT AGREEMENT [UNLESS EXPLICITLY STATED OTHERWISE]') }}
        </h2>
        <ol class="stack" type="a">
            <li class="stack">
                {{ safe_markdown('**The Platform is a Venue.** The Platform serves as a venue that:') }}
                <ol type="i">
                    <li>{{ __('provides digital tools for Project Proponents to propose Projects, enter into relationships with Consultants who can assist them with the design and development of such Projects; and') }}
                    </li>
                    <li>{{ __('provides digital tools for Consultants to advertise their services, enter into relationships with Project Proponents, assist in the design and development of Projects.') }}
                    </li>
                </ol>
                <p>
                    <strong>{{ __('USE OF THE PLATFORM, ANY SERVICE CONTENT AND THE SERVICES IS AT USER’S DISCRETION AND RISK.') }}</strong>
                </p>
            </li>
            <li>{{ safe_inlineMarkdown('**Projects and Project Agreements.** IRIS is not a party to any agreements, transactions, projects or arrangements (“**Project Agreements**”) entered into between Project Proponents and Consultants unless explicitly agreed to. While IRIS provides the Platform and Services enabling you to engage in Projects and facilitate the payment for certain services, IRIS is not involved in any way in the Project other than through the provision of the Services, nor is IRIS party to the Project Agreements, except for any transactions for services expressly entered into between IRIS and Users. You understand and agree that IRIS will not be liable under any circumstances for the content or enforcement of any Project Agreement between you and another User, as applicable. You further understand and agree that while IRIS requires Users to submit true, accurate, current and complete information, IRIS cannot guarantee the qualifications of any Consultant or the success of any Project.') }}
            </li>
            <li class="stack">
                {{ safe_markdown('**Disclaimer.** All notices, Project Agreements and related documents made available on or through the Platform, either by IRIS, other users, or generated as a third party service on or through the Platform, are not legally reviewed, endorsed or approved by IRIS and are used at your sole risk. We make no representation or warranty concerning the enforceability of any agreements signed or exchanged by electronic means through tools or functions made available on or through the Platform.') }}
                <p>
                    <strong>{{ __('USER SHOULD SEEK LEGAL ADVICE BEFORE UTILIZING OR ENTERING INTO ANY AGREEMENTS OR OTHER DOCUMENTS OR RELYING ON ANY INFORMATION MADE AVAILABLE THROUGH THE PLATFORM. IRIS DOES NOT PROVIDE LEGAL ADVICE OR ANY ADVICE CONCERNING ANY LEGAL DOCUMENTS OFFERED BY A USER TO ANOTHER USER, INCLUDING PROJECT AGREEMENTS.') }}</strong>
                </p>
            </li>
            <li>{{ safe_inlineMarkdown('**Applicable Laws.** You agree that you are responsible for, and agree to abide by, all laws, rules, and regulations applicable to your use of the Platform, your use of any tool, service or product offered on the Platform and any transaction or agreement you enter into on the Platform or in connection with your use of the Platform. You further agree that you are responsible for and agree to abide by all laws, rules and regulations applicable to the Project, including any and all laws, rules, regulations or other requirements relating to taxes, credit cards, data and privacy, accessibility, and compliance with all anti-discrimination laws, as applicable.') }}
            </li>
            <li>{{ safe_inlineMarkdown('**Compliance with Government Investigations.** You acknowledge that, even though we are not a party to any Project Agreement, unless otherwise stated in a particular Project Agreement, and assume no liability for legal or regulatory compliance pertaining to any Project listed on the Platform, there may be circumstances where we are nevertheless legally obligated (as we may determine in our sole discretion) to provide information relating to your Project in order to comply with governmental bodies in relation to investigations, litigation or administrative proceedings, and we may choose to comply with or disregard such obligation in our sole discretion.') }}
            </li>
            <li>{{ safe_inlineMarkdown('**Payments.** Any payments between Users are for services provided by Consultants or another party separate from IRIS. Payment amounts are determined solely between Users. While IRIS provides the Platform through which activities are facilitated, IRIS is not liable for, party to, or responsible for those transactions even though IRIS may receive a commission from such payments and assist in the administration of remuneration from Project Proponents to Consultants. User agrees that issues or concerns, including non-receipt of payment, any discrepancy in payment amounts, or delays in payment, will be addressed by contacting the relevant User or third party.') }}
            </li>
        </ol>

        <h2 class="counter__item">{{ __('ACCESS AND USE OF THE PLATFORM') }}</h2>
        <ol class="stack" type="a">
            <li>{{ safe_inlineMarkdown('**Registration.** If you choose to register for the Platform, you agree to provide and maintain true, accurate, current and complete information about yourself as prompted by the Platform’s registration form to create your user account, including your name or the name of the organization you represent, email address, password and phone number (“**Personal Contact Information**”). If you are under 13 years of age, you are not authorized to use the Platform, with or without registering. In addition, if you are under 18 years old, you may use the Platform, with or without registering, only with the approval and supervision of your parent or guardian. In addition, you may also provide certain additional information in order to enable the Platform to match you to appropriate Projects, including information pertaining to location, identity, experience of disability, citizenship status (“**Demographic Information**”).  If you provide any Personal Contact Information or Demographic Information that is untrue, inaccurate, not current, or incomplete, or if IRIS has a reasonable ground to suspect that such information is untrue, inaccurate, not current, or incomplete, IRIS has the right, in its sole discretion, to suspend or terminate your account and refuse any and all current or future use of the Platform (or any portion thereof). If you invite anyone under the age of 18 (an “**Underaged Person**”) to use the Platform, you agree to be solely responsible for the Underaged Person’s use of same.') }}
            </li>
            <li>{{ safe_inlineMarkdown('**Password and Security.** You are responsible for maintaining the confidentiality of your Registration Information (and the Registration Information of related usernames) and are fully responsible for any and all activities that occur under your Personal Contact Information. You agree to (a) immediately notify IRIS of any unauthorized use of your Registration Information or any other breach of security, and (b) ensure that you log out from the Platform at the end of each session when accessing the Platform. IRIS will not be liable for any loss or damage arising from your failure to comply with this Subsection 3(b). Further, you agree not to allow third parties to access the Platform or IRIS’ website through your username and password.') }}
            </li>
            <li>{{ safe_inlineMarkdown('**Modification of the Platform.** IRIS reserves the right to modify the form and nature of the Platform, or discontinue, temporarily or permanently, the Platform (or any part thereof) with or without notice. You agree that IRIS will not be liable to you or to any third party for any modification, suspension or discontinuance of the Platform. IRIS may also, in its sole discretion, restrict access to the website for any reason.') }}
            </li>
            <li>{{ safe_inlineMarkdown('**General Practices.** You acknowledge that IRIS may establish general practices and limits concerning use of the Platform, including without limitation the maximum period of time that data or other content, will be retained by the Platform and the maximum storage space that will be allotted on IRIS’ or its partners’, affiliates’, or service providers’ servers on your behalf. You agree that IRIS has no responsibility or liability for the deletion or failure to store any data or other content maintained or uploaded by the Platform. You acknowledge that IRIS reserves the right to terminate accounts that are inactive for an extended period of time. You further acknowledge that IRIS reserves the right to change these general practices and limits at any time, in its sole discretion, with or without notice.') }}
            </li>
            <li>{{ safe_inlineMarkdown('**Platform Access.** In order to use the Platform, you must obtain Internet access, either directly or through devices that access web-based content and pay any service fees associated with such access. You are solely responsible for paying such fees. In addition, you must provide all equipment necessary to make such Internet connection, including a computer and modem or other access device. You are solely responsible for providing such equipment. You acknowledge that while IRIS may not currently have set a fixed upper limit on the number of transmissions you may send or receive through the Platform or on the amount of storage space used for the provision of the Platform’s services, such fixed upper limits may be set by IRIS at any time, at IRIS’ discretion.') }}
            </li>
        </ol>

        <h2 class="counter__item">{{ __('PRIVACY AND CONFIDENTIALITY.') }}</h2>
        {{ safe_markdown(
            'IRIS respects your right to privacy. Any personal information collected in connection with your registering on and use of the Platform shall be done in accordance with [IRIS’ Privacy Policy](:url), which forms part of these Terms. User is responsible for its compliance with all applicable privacy laws and covenants that all personal data and information provided to IRIS or uploaded to the Platform is provided in compliance with applicable laws.',
            ['url' => localized_route('about.privacy-policy')],
        ) }}

        <h2 class="counter__item">{{ __('SECURITY.') }}</h2>
        <p>{{ __('IRIS has implemented security policies and practices that are designed to protect the security and integrity of the Platform; however, IRIS does not guarantee the security of the Platform or the security or integrity of any communications between you and the Platform. You acknowledge that you alone are responsible for ensuring you have a secure connection to access the Platform and implementing security safeguards to protect yourself when accessing and using the Platform, including taking precautions against viruses, worms, trojan horses and other items of a disabling or destructive nature.') }}
        </p>

        <h2 class="counter__item">{{ __('CONDITIONS OF USE.') }}</h2>
        <ol class="stack" type="a">
            <li>
                {{ safe_markdown('**Unauthorized uses of the Platform.** You agree not to:') }}
                <ol class="stack" type="i">
                    <li>{{ __('Engage in the commercial use of the Platform except for the purposes set out in these Terms;') }}
                    </li>
                    <li>{{ __('interfere with or disrupt the Platform or servers or networks connected to the Platform, or disobey any requirements, procedures, policies or regulations of networks connected to the Platform; or violate any applicable provincial, territorial, local, national or international law, or any regulations having the force of law;') }}
                    </li>
                    <li>{{ __('modify, copy, reproduce, reverse engineer, frame, rent, lease, loan, sell, distribute, publish, or create derivative works based on the Platform or the Service Content (as defined below), in whole or in part;') }}
                    </li>
                    <li>{{ __('engage in or use any data mining, robots, scraping or similar data gathering or extraction methods;') }}
                    </li>
                    <li>{{ __('collect, aggregate, copy, scrape, duplicate, display or derivatively use the Platform;') }}
                    </li>
                    <li>{{ __('transmit any viruses, worms, defects, Trojan horses, or other items of a destructive nature;') }}
                    </li>
                    <li>{{ __('implement any measures to circumvent any tools or resources IRIS has used to block you from accessing all or some of the Platform (e.g., by masking your IP address or using a proxy IP address);') }}
                    </li>
                    <li>{{ __('impersonate any person or entity, or falsely state or otherwise misrepresent your affiliation with a person or entity;') }}
                    </li>
                    <li>{{ __('solicit personal information from any Underaged Person; or harm such persons in any way;') }}
                    </li>
                    <li>{{ __('harvest or collect email addresses or other contact information of other users from the Platform by electronic or other means for the purposes of sending unsolicited emails or other unsolicited communications;') }}
                    </li>
                    <li>{{ __('advertise or offer to sell or buy any goods or services for any business purpose that is not specifically authorized;') }}
                    </li>
                    <li>{{ __('further or promote any criminal activity or enterprise or provide instructional information about illegal or potentially illegal activities;') }}
                    </li>
                    <li>{{ __('obtain or attempt to access or otherwise obtain any materials or information through any means not intentionally made available or provided for through the Platform;') }}
                    </li>
                    <li>{{ __('add your own headers, forge headers, or otherwise manipulate identifiers in a manner not permitted by the Platform, in order to disguise the origin of any User Content transmitted through the Platform;') }}
                    </li>
                    <li>{{ __('"stalk" or otherwise harass another person;') }}</li>
                    <li>{{ __('use any information received through the Platform to attempt to identify other users, to contact other users (other than through features for contacting other users), or for any forensic use;') }}
                    </li>
                    <li>{{ __('download any file posted by another user of the Platform that you know, or reasonably should know, cannot legally be distributed in such manner;') }}
                    </li>
                    <li>{{ __('violate these Terms, any code of conduct or other guidelines which may be applicable for any particular area of the Platform, including those that have been communicated to you by anyone affiliated with IRIS; or') }}
                    </li>
                    <li>{{ __('violate any of the obligations contained in these Terms, including, but not limited to, those contained in Section 9 hereof pertaining to intellectual property rights.') }}
                    </li>
                </ol>
            </li>
            <li>{{ safe_inlineMarkdown('**Invoices.** IRIS will invoice Project Proponents the annual subscription amount owed to access and use the Platform. Project Proponents hereby agree to pay any undisputed amounts to IRIS within 30 days of such invoice.') }}
            </li>
            <li>{{ safe_inlineMarkdown('**Suspension.** You acknowledge and agree that you are solely responsible for (and that IRIS has no responsibility to you or to any third party for) any breach of your obligations under these Terms or for the consequences (including any loss or damage which IRIS may suffer) of any such breach. If you breach these Terms and/or IRIS has a reasonable ground to suspect that you have violated the terms of these Terms, IRIS may suspend or terminate your access to and use of the Platform (or any portion thereof).') }}
            </li>
        </ol>

        <h2 class="counter__item">{{ __('USER CONTENT.') }}</h2>
        <ol class="stack" type="a">
            <li>{{ safe_inlineMarkdown('You are solely responsible for all Content that you Upload or make available to IRIS (“**User Content**”).') }}
            </li>
            <li>{{ __('You acknowledge that IRIS does not independently evaluate, investigate, or otherwise conduct any due diligence regarding any User Content provided by other Users and IRIS has no liability to User for any damage or loss concerning the accuracy of User Content or User’s access to, or use of, or reliance on, any User Content.') }}
            </li>
            <li>{{ __('User represents and warrants that all User Content made available to and through the Platform: (i) is true, accurate and current; (ii) belongs to User and that User owns all right, title and interest in and to such User Content, including, without limitation, all copyrights and rights of publicity contained therein; (iii) does not and will not, directly or indirectly, infringe, violate or breach any duty toward or rights of any person or entity, including without limitation any copyright, trademark, service mark, trade secret, other intellectual property, publicity or privacy right; (iv) provided in compliance with all applicable laws, regulations, regulatory guidelines, policies and codes and industry guidelines, policies and codes.') }}
            </li>
            <li>{{ __('You agree not to Upload any content that (1) infringes any intellectual property or other proprietary rights of any party, (2) constitutes inside information, proprietary or confidential information under any law, contractual or fiduciary relationship or was learned or disclosed as part of employment relationships or under nondisclosure agreements; (3) contains malicious software code, including viruses or any other computer code, files or programs designed to interrupt, destroy or limit the functionality of any computer software or hardware or telecommunications equipment, (4) poses or creates a privacy or security risk to the platform or any person, (5) constitutes unsolicited or unauthorized advertising, promotional materials, commercial activities and/or sales, “junk mail,” “spam,” “chain letters,” “pyramid schemes,” “contests,” “sweepstakes,” or any other form of solicitation, (6) is unlawful, harmful, threatening, abusive, harassing, tortious, excessively violent, defamatory, vulgar, obscene, pornographic, libelous, invasive of another’s privacy, hateful racially, ethnically or otherwise objectionable, or (7) in the sole judgment of IRIS, is objectionable or which restricts or inhibits any other person from using or enjoying the Platform, or which may expose IRIS or its users to any harm or liability of any type.') }}
            </li>
            <li>{{ __('You acknowledge that IRIS and its designees shall have the right (but not the obligation) in their sole discretion to pre-screen, review, filter, modify, refuse, or move any User Content that is available via the Platform. Without limiting the foregoing, IRIS and its designees shall have the right to remove any User Content that violates these Terms or is deemed by IRIS, in its sole discretion, to be otherwise objectionable. IRIS reserves the right to investigate and take appropriate action if, in IRIS’ sole discretion, any User Content Uploaded is illegal or prohibited User Content (including User Content that includes any of the content listed below). Such actions may include without limitation, removing the offending Content from the Platform, suspending or terminating your access to the Platform and reporting you to the law enforcement authorities.') }}
            </li>
        </ol>

        <h2 class="counter__item">{{ __('THIRD PARTY CONTENT AND SERVICES.') }}</h2>
        <ol class="stack" type="a">
            <li>{{ safe_inlineMarkdown('**Third Party Content.** Certain third-party service providers, including advertisers, payment processing vendors, and other service providers (“**Third Party Provider**”) may Upload Content to the Platform (“**Third Party Content**”). IRIS does not control or own, is not responsible for, and does not guarantee the accuracy, integrity, or quality of such Third Party Content. All issues or concerns, technical or otherwise, with Third Party Content must be addressed by contacting the applicable Third Party Provider directly and not through IRIS. Under no circumstances will IRIS be liable in any way for any Third Party Content, including, but not limited to, any errors or omissions in any such Third Party Content, or for any loss or damage of any kind incurred as a result of the use of any such content posted, emailed, or otherwise transmitted via the Platform. You acknowledge and agree that you rely on the use of any Third Party Content, at your own risk, including any reliance on the accuracy, completeness, or usefulness of such content.') }}
            </li>
            <li>{{ safe_inlineMarkdown('**Third Party Services.** In addition to Third Party Content, the Platform may allow you to connect with Third Party Providers in order to purchase goods and/or sign up for, services from or participate in promotions of a Third Party (“**Third Party Service**”). You acknowledge and agree that the provision of any Third Party Service is provided solely by the applicable Third Party, and not IRIS, and is subject to the terms and conditions of such Third Party (“**Third Party Terms**”). Unless otherwise stated in a particular agreement, IRIS is not a party to any Third Party Terms and shall have no liability, obligation, responsibility or duty for any Third Party Service between you and any such Third Party Provider. Your use of any Third Party Service is solely at your risk.') }}
            </li>
            <li>{{ safe_inlineMarkdown('**Third Party Payment Processors.** If as part of the Services the Platform offers Project Proponents the ability to pay Consultants directly, any such payments will made using a Third Party Service provided by a Third Party Provider. The processing of payments or credits, as applicable will be subject to the Third Party Terms of the Third Party Provider and applicable credit card issuer. IRIS is not responsible for any errors by the payment processor and Users will resolve any disputes for amounts charged directly with the payment processor. In connection with Users’ use of the Platform and receipt of Services, IRIS may obtain certain transaction details, which IRIS will only use in accordance with these Terms. Users will be responsible to obtain all necessary authorizations and consents to process User credit cards and other permitted payment cards and methods.') }}
            </li>
        </ol>

        <h2 class="counter__item">{{ __('INTELLECTUAL PROPERTY RIGHTS.') }}</h2>
        <ol class="stack" type="a">
            <li>{{ safe_markdown('**Service Content.** You acknowledge and agree that all right, title, and interest (including all intellectual property rights) in and to the Platform and any Content on the Platform (excluding any User Content and any Third Party Content), including but not limited to graphics, design, compilation, interfaces, computer code, products, software (these content, the “**Service Content**”) are and will remain the sole and exclusive property of IRIS or its third party licensors, as applicable. Any rights not expressly granted under these Terms are reserved by IRIS. Any use of the Platform or the Service Content other than as specifically authorized herein is strictly prohibited. To the extent that IRIS identifies any Service Content or other information on the Platform as confidential, you agree that you will not disclose such information without IRIS’ prior written consent. ') }}
            </li>
            <li>{{ safe_markdown('**Trademarks.** The IRIS name and logos and The Accessibility Exchange name and logos are trademarks and business names of the Institute for Research and Development on Inclusion and Society (collectively the “**IRIS Trademarks**”). Other company, product, and service names and logos used and displayed via the Platform are trademarks of their respective owners who may or may not endorse or be affiliated with or connected to IRIS. Nothing in these Terms or the Platform should be construed as granting, by implication, estoppel, or otherwise, any license or right to use any of the IRIS Trademarks displayed on the Platform without our prior written permission in each instance. All goodwill generated from the use of the IRIS Trademarks will inure to our exclusive benefit.') }}
            </li>
            <li>{{ __('You agree that you shall not remove, obscure, or alter any proprietary rights notices (including copyright and trademark notices) that may be affixed to or contained within the Platform or in or on any Service Content.') }}
            </li>
        </ol>

        <h2 class="counter__item">{{ __('LICENSES.') }}</h2>
        <ol class="stack" type="a">
            <li>{{ safe_inlineMarkdown('**Use of the Platform.** IRIS authorizes You to access and use the Platform solely for the purpose of searching, accessing, downloading and reviewing Platform content (in print, audio, video or other provided format) for informational purposes only and solely for your own use.') }}
            </li>
            <li>{{ safe_inlineMarkdown('**User Content.** IRIS does not claim ownership of any User Content you provide to IRIS (including feedback and suggestions) via the Platform. Unless otherwise specified, you retain copyright and any other rights you already hold over User Content that you create and submit, post, or display on or through the Platform. However, by submitting, posting, or displaying User Content, you give IRIS and its affiliates and their successors and assigns a perpetual, irrevocable, worldwide, royalty-free, fully paid up, sublicensable and non-exclusive license to reproduce, adapt, modify, translate, publish, publicly perform, publicly display, distribute, Upload, store, edit, reformat, otherwise use and create derivative works from any User Content that you submit, post, or display on or through the Platform.') }}
            </li>
            <li>{{ safe_inlineMarkdown('**Disclosure of Submissions.** You acknowledge and agree that any questions, comments, suggestions, ideas, feedback or other information about the Platform (“Submissions”), provided by you to IRIS are non-confidential and IRIS will be entitled to the unrestricted use and dissemination of these Submissions for any purpose, commercial or otherwise, without acknowledgment or compensation to you.') }}
            </li>
            <li>{{ safe_inlineMarkdown('**Preservation of Content.** You acknowledge and agree that IRIS may preserve User Content and may also disclose User Content if required to do so by law or in the good faith belief that such preservation or disclosure is reasonably necessary to: (a) comply with legal process, applicable laws or government requests; (b) enforce these Terms; (c) respond to claims that any content violates the rights of third parties; or (d) protect the rights, property, or personal safety of IRIS, its users and the public.') }}
            </li>
        </ol>

        <h2 class="counter__item">{{ __('USER REPRESENTATIONS.') }}</h2>
        <ol class="stack" type="a">
            <li>{{ __('You understand and agree that your participation in the Platform is entirely voluntary, and that you are under no obligation to agree to these Terms unless you wish to access the Platform.') }}
            </li>
            <li>{{ __('You represent that you are of legal age, as set forth in Subsection 3(a), to use the Platform.') }}
            </li>
            <li>{{ __('You agree to take responsibility for all possible consequences resulting from your sharing with others access to your User Content.') }}
            </li>
            <li>{{ __('You understand and agree that all your Registration Information will be stored in IRIS’ databases and will be processed in accordance with these Terms.') }}
            </li>
            <li>{{ __('You agree that you have the authority, under the laws of the province or jurisdiction in which you reside, to provide these representations. In case of breach of any one of these representations IRIS has the right to suspend or terminate your account and refuse any and all current or future use of the Platform (or any portion thereof).   You hereby agree to defend and indemnify IRIS and its affiliates against any liability, costs, or damages, including reasonable attorneys’ fees, arising out of the breach of the representations.') }}
            </li>
        </ol>

        <h2 class="counter__item">{{ __('INDEMNITY AND RELEASE.') }}</h2>
        <ol class="stack" type="a">
            <li>{{ safe_inlineMarkdown('(a)	You agree to release, indemnify and hold IRIS and its affiliates and their officers, employees, directors and agents (collectively, “**Indemnitees**”) harmless from any and all losses, damages, expenses, liability, or costs, including reasonable attorneys’ fees, rights, claims, actions of any kind and injury (including death) arising out of or relating to (i) your access, use and/or misuse of the Platform; (ii) any of your User Content, including any claim by a third party that the display or other use of User Content infringes the intellectual property or other rights of a third party; (iii) your violation of these Terms or your violation of any rights of another; (iv) access and use of your Registration Information by any third parties; (v) any use of your Registration Information by an Underaged Person; or (vi) claims asserted against the Indemnitees by another User or third party arising as a consequence of User’s act, omission or conduct in relation to the use of the Platform and Services, or a Third Party Service, or any activities ancillary thereto.') }}
            </li>
            <li>{{ __('IRIS shall have the right, in its sole discretion, to participate in the defense of any third party claim. User will not, without the prior written approval of IRIS, settle, dispose or enter into any proposed settlement or resolution of any claim (whether having been finally adjudicated or otherwise) brought against User, if such settlement or resolution results in any obligation or liability for or admission of wrongdoing by IRIS.') }}
            </li>
            <li>{{ __('Notwithstanding the foregoing, you will have no obligation to indemnify or hold harmless any Indemnitee from or against any liability, losses, damages or expenses incurred as a result of any action or inaction of such Indemnitee.') }}
            </li>
        </ol>

        <h2 class="counter__item">{{ __('DISCLAIMER OF WARRANTIES.') }}</h2>
        <p>{{ __('YOUR USE OF THE PLATFORM IS AT YOUR SOLE RISK. TO THE MAXIMUM EXTENT PERMITTED BY LAW, THE PLATFORM IS PROVIDED ON AN “AS IS”, “WITH ALL FAULTS” AND “AS AVAILABLE” BASIS AND AT USER’S SOLE RISK. IRIS EXPRESSLY DISCLAIMS ALL REPRESENTATIONS AND WARRANTIES OF ANY KIND, WHETHER EXPRESS, IMPLIED OR STATUTORY, INCLUDING, BUT NOT LIMITED TO THE IMPLIED WARRANTIES AND CONDITIONS OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE, TITLE AND NON-INFRINGEMENT. IRIS MAKES NO WARRANTY THAT (I) THE PLATFORM WILL MEET YOUR REQUIREMENTS, (II) THE PLATFORM WILL BE UNINTERRUPTED, TIMELY, UNFAILINGLY SECURE, OR ERROR-FREE, (III) INFORMATION OBTAINED FROM YOUR USE OF THE PLATFORM WILL BE ACCURATE, ERROR FREE, RELIABLE, OR MEET THE NEEDS OF OR REQUIREMENTS OF USERS; (IV) WILL ALWAYS BE AVAILABLE, ACCESSIBLE, UNINTERRUPTED, TIMELY, SECURE OR FREE OF MALICIOUS CODE OR VIRUS; (IV) INFORMATION OR MATERIALS OBTAINED BY YOU THROUGH THE PLATFORM WILL MEET YOUR EXPECTATIONS.') }}
        </p>
        <p>{{ __('ANY SERVICE CONTENT DOWNLOADED OR OTHERWISE OBTAINED THROUGH THE USE OF THE PLATFORM IS DONE AT YOUR OWN DISCRETION AND RISK AND YOU WILL BE SOLELY RESPONSIBLE FOR ANY DAMAGE TO YOUR COMPUTER SYSTEM OR LOSS OF DATA THAT RESULTS FROM THE DOWNLOAD OF ANY SUCH MATERIAL.') }}
        </p>
        <p>{{ __('NO ADVICE OR INFORMATION, WHETHER ORAL OR WRITTEN, OBTAINED BY YOU FROM IRIS OR THROUGH ACCESS TO OR USE OF THE PLATFORM OR SERVICES SHALL CREATE ANY WARRANTY NOT EXPRESSLY STATED IN THE TERMS OF SERVICE.') }}
        </p>

        <h2 class="counter__item">{{ __('LIMITATION OF LIABILITY.') }}</h2>
        <p>{{ __('YOU EXPRESSLY UNDERSTAND AND AGREE THAT IRIS WILL NOT BE LIABLE FOR ANY INDIRECT, INCIDENTAL, SPECIAL, CONSEQUENTIAL, EXEMPLARY DAMAGES, OR DAMAGES FOR LOSS OF PROFITS INCLUDING BUT NOT LIMITED TO, DAMAGES FOR LOSS OF GOODWILL, USE, DATA OR OTHER INTANGIBLE LOSSES (EVEN IF IRIS HAS BEEN ADVISED OF THE POSSIBILITY OF SUCH DAMAGES), WHETHER BASED ON CONTRACT, TORT, NEGLIGENCE, STRICT LIABILITY OR OTHERWISE, INCLUDING BUT NOT LIMITED TO THOSE DAMAGES RESULTING FROM: (I) THE USE OR THE INABILITY TO USE THE PLATFORM; (II) THE COST OF PROCUREMENT OF SUBSTITUTE GOODS AND SERVICES RESULTING FROM ANY GOODS, DATA, INFORMATION OR SERVICES PURCHASED OR OBTAINED OR MESSAGES RECEIVED OR TRANSACTIONS ENTERED INTO THROUGH OR FROM THE PLATFORM; (III) UNAUTHORIZED ACCESS TO OR ALTERATION OF YOUR TRANSMISSIONS OR DATA; (IV) STATEMENTS OR CONDUCT OF ANY THIRD PARTY ON THE PLATFORM; (V) ANY ACTION YOU TAKE BASED ON THE INFORMATION YOU RECEIVE IN THROUGH OR FROM PLATFORM; (VI) YOUR FAILURE TO KEEP YOUR PASSWORD OR ACCOUNT DETAILS SECURE AND CONFIDENTIAL; (VII) THE IMPROPER AUTHORIZATION FOR THE PLATFORM BY SOMEONE CLAIMING SUCH AUTHORITY; OR (VII) ANY OTHER MATTER RELATING TO THE PLATFORM.') }}
        </p>
        {{ safe_markdown('IN NO EVENT WILL IRIS’ TOTAL AGGREGATE LIABILITY, FOR ANY AND ALL CLAIMS ARISING OUT OF OR RELATED TO THIS AGREEMENT, THE PLATFORM, PLATFORM CONTENT AND ANY SERVICES, WHETHER IN CONTRACT, TORT OR UNDER ANY OTHER THEORY OF LIABILITY, EXCEED THE LESSER OF (I) ANY FEES PAID BY THE USER TO IRIS FOR THE SERVICES DURING **[THE TWELVE (12) MONTH PERIOD IMMEDIATELY PRECEDING THE DATE ON WHICH THE CAUSE OF ACTION AROSE; AND (II) SIX  HUNDRED AND TWENTY-FIVE DOLLARS (CDN$625.00)]**. ') }}
        <p>{{ __('SOME JURISDICTIONS DO NOT ALLOW THE DISCLAIMER OR EXCLUSION OF CERTAIN WARRANTIES OR THE LIMITATION OR EXCLUSION OF LIABILITY FOR INCIDENTAL OR CONSEQUENTIAL DAMAGES. ACCORDINGLY, SOME OF THE ABOVE LIMITATIONS SET FORTH ABOVE MAY NOT APPLY TO YOU OR BE ENFORCEABLE WITH RESPECT TO YOU.') }}
        </p>
        <p>{{ __('IF YOU ARE DISSATISFIED WITH ANY PORTION OF THE PLATFORM OR WITH THESE TERMS OF SERVICE, YOUR SOLE AND EXCLUSIVE REMEDY IS TO DISCONTINUE USE OF THE PLATFORM.') }}
        </p>

        <h2 class="counter__item">{{ __('USER DISPUTES.') }}</h2>
        <p>{{ __('You agree that you are solely responsible for your interactions with any other user in connection with the Platform and that IRIS will have no liability or responsibility with respect thereto. IRIS reserves the right, but has no obligation, to become involved in any way with disputes between you and any other user of the Platform.') }}
        </p>

        <h2 class="counter__item">{{ __('TERMINATION.') }}</h2>
        <ol class="stack" type="a">
            <li>{{ safe_inlineMarkdown('**Term.** This Agreement will continue for a term of 12 months and automatically renew at the end of such period, subject to your continued payment of any subscription amounts owed.') }}
            </li>
            <li>{{ safe_inlineMarkdown('**For Cause.** IRIS may, in its sole discretion, immediately terminate these Terms or, without limiting its other rights and remedies, suspend User’s access to the Platform and Services if User fails to comply with any provision of these Terms. In the event that IRIS terminates or suspends your access to the Platform or Services or these Terms under this Section, you understand and agree that you will not receive a refund of, or credit for, any fees paid.') }}
            </li>
            <li>
                {{ safe_markdown('**Other Termination.** IRIS reserves the right to terminate these Terms, suspend or terminate User’s access to the Platform and/or any Services with or without notice, or deactivate or delete your account including all related information to User for any reason, including:') }}
                <ol class="stack" type="i">
                    <li>{{ __('for lack of use;') }}</li>
                    <li>{{ __('if IRIS is required to do so by law (for example, where the provision of the Platform to you is, or becomes, unlawful);') }}
                    </li>
                    <li>{{ __('if IRIS is transitioning to no longer providing the Platform to users in the region or province in which you reside or from which you use the Platform; or') }}
                    </li>
                    <li>{{ __('if the provision of the Platform to you by IRIS is, in IRIS’ opinion, no longer commercially or financially viable.') }}
                    </li>
                </ol>
                <p>{{ __('In the event that IRIS terminates or suspends the User’s access to the Services or this Agreement under this Subsection 16(b), User will receive a pro-rata refund of any prepaid fees paid to IRIS for which Services have not been provided. IRIS shall not be liable to you or to any third party for any suspension or discontinuance of the Services or the Platform, including on account of any expenditures or investments or other commitments made or actions taken in reliance on the expected continuation of the Services or Platform. Any suspected fraudulent, abusive or illegal activity that may be grounds for termination of your use of the Platform and may be referred to appropriate law enforcement authorities.') }}
                </p>
            </li>
            <li>{{ safe_inlineMarkdown('**Termination for Convenience.** You may terminate these Terms for convenience upon 30 days prior written notice to IRIS. At the end of such 30-day period, IRIS will de-activate your account and to the extent there are outstanding amounts owed from you or to you, either invoice you for any outstanding amounts owed or provide a pro-rata reimbursement of your annual subscription.') }}
            </li>
        </ol>

        <h2 class="counter__item">{{ __('SURVEYS.') }}</h2>
        <p>{{ __('IRIS desires to continually improve the Platform or Service and may, in accordance with the IRIS Privacy Policy, contact you regarding your use of the Platform or Service. IRIS may also contact you about other studies it has conducted, is conducting, or may conduct in the future, or other services it offers or may offer in the future.') }}
        </p>

        <h2 class="counter__item">{{ __('GENERAL') }}</h2>
        <ol class="stack" type="a">
            <li>{{ safe_inlineMarkdown('**Entire Agreement.** These Terms constitute the entire agreement between you and IRIS and govern your use of the Platform, superseding any prior agreements between you and IRIS with respect to the Platform. You also may be subject to additional terms and conditions that may apply when you use affiliate or Third Party Services, Third Party Content or third party software.') }}
            </li>
            <li>{{ safe_inlineMarkdown('**Language.**  It is the express wish of the parties hereto that the Terms be drawn up in English. The parties hereto hereby waive any right to use and rely upon any other language.') }}
            </li>
            <li>{{ safe_inlineMarkdown('**Governing Law.** These Terms will be governed by the laws of the Province of Ontario without regard to its conflict of law provisions. With respect to any disputes or claims not subject to arbitration, as set forth above, you and IRIS agree to submit to the personal and exclusive jurisdiction of the province of Ontario. The failure of IRIS to exercise or enforce any right or provision of these Terms will not constitute a waiver of such right or provision. Recognizing the global nature of the Internet, you agree to comply with all local rules and laws regarding your use of the Platform, including as it concerns online conduct and acceptable content.') }}
            </li>
            <li>{{ safe_inlineMarkdown('**Severability.** If any provision of these Terms is found by a court of competent jurisdiction to be invalid, the parties nevertheless agree that the court should endeavor to give effect to the parties’ intentions as reflected in the provision, and the other provisions of these Terms remain in full force and effect.') }}
            </li>
            <li>{{ safe_inlineMarkdown('**Limitation Period.** You agree that regardless of any statute or law to the contrary, any claim or cause of action arising out of or related to use of the Platform or these Terms must be filed within one (1) year after such claim or cause of action arose or be forever barred.  A printed version of this agreement and of any notice given in electronic form will be admissible in judicial or administrative proceedings based upon or relating to this agreement to the same extent and subject to the same conditions as other business documents and records originally generated and maintained in printed form.') }}
            </li>
            <li>{{ safe_inlineMarkdown('**Assignment.** You may not assign these Terms without the prior written consent of IRIS, but IRIS may assign or transfer these Terms, in whole or in part, without restriction.') }}
            </li>
            <li>{{ safe_inlineMarkdown('**Interpretation.** The section titles in these Terms are for convenience only and have no legal or contractual effect.') }}
            </li>
            <li>{{ safe_inlineMarkdown('**Waiver.** No failure or delay by IRIS in exercising any right hereunder will waive any further exercise of that right.') }}
            </li>
            <li>
                {{ safe_inlineMarkdown(
                    '**Notices.** All notices or approvals required or permitted under these Terms will be in writing and delivered by email transmission, and in each instance will be deemed given upon receipt. All notices or approvals will be sent to IRIS at **[<:email>]**. Notices to you may be made via either email or regular mail. The Platform may also provide notices to you of changes to these Terms or other matters by displaying notices or links to notices generally on the Platform.',
                    ['email' => $email],
                ) }}
            </li>
            <li>
                {{ safe_inlineMarkdown(
                    '**Modification.** We reserve the right, at our sole discretion, to change or modify portions of these Terms at any time. If we do this, we will post the changes on **[<:url>]** and will indicate at the top of the Terms page the date these terms were last revised. We will also endeavor to notify you, either through the Platform user interface, in an email notification or through other reasonable means. Any such changes will become effective no earlier than fourteen (14) days after they are posted, except that changes addressing new functions of the Platform or changes made for legal reasons will be effective immediately. Your continued use of the Platform after the date any such changes become effective constitutes your acceptance of the new Terms.',
                    ['url' => $appURL],
                ) }}
            </li>
        </ol>

        <h2 class="counter__item">{{ __('QUESTIONS') }}</h2>
        {{ safe_markdown('If you have any questions, you understand that you may contact IRIS via email at <:email>.', [
            'email' => $email,
        ]) }}
    </div>

</x-app-layout>
