<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PrivacyPolicySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Page::firstOrCreate(
            [
                'title->en' => 'Privacy Policy',
            ],
            [
                'title->fr' => 'Politique de confidentialité',
                'content->en' => '
**The Accessibility Exchange is a website that is run by the Institute for Research and Development on Inclusion and Society (also called IRIS). IRIS is responsible for information about you that you provide to The Accessibility Exchange. This document explains how IRIS handles information about you. If you have questions about this document please email <:email_privacy>**

Institute for Research and Development on Inclusion and Society (“IRIS”, “we”, “us” or “our”) takes the privacy of personal information very seriously. This Privacy Policy applies to and describes the manner in which we collect, use, disclose, and otherwise treat personal information in the course of providing The Accessibility Exchange (the “Platform”), which includes the website portal located at [<:home>].

{.toc .ignore-counter}
## Table of Contents

1. [ABOUT OUR PLATFORM](#about-our-platform)
2. [COLLECTION AND USE OF PERSONAL INFORMATION](#collection-and-use-of-personal-information)
3. [DISCLOSURE OF PERSONAL INFORMATION](#disclosure-of-personal-information)
4. [INFORMATION COLLECTED AUTOMATICALLY](#information-collected-automatically)
5. [THIRD PARTY SITES](#third-party-sites)
6. [SECURITY OF PERSONAL INFORMATION](#security-of-personal-information)
7. [RETENTION OF PERSONAL INFORMATION](#retention-of-personal-information)
8. [YOUR RIGHTS](#your-rights)
9. [UPDATES TO OUR PRIVACY POLICY](#updates-to-our-privacy-policy)
10. [CONTACT US](#contact-us)

## ABOUT OUR PLATFORM

The Accessibility Exchange is an online platform that brings people together to:

* build strong and effective accessibility plans
* share information resources
* learn about accessibility through online training and courses
* develop innovative solutions to barriers
* and monitor progress on building an inclusive and accessible Canada,.

The platform enables diverse people with disabilities and Deaf people (including, Indigenous, racialized, Black, 2SLGBTQI+ and other identities) and their supporters and community organizations to

* access certain consultation services (**“Services”**) such as consulting on accessibility plans of federally regulated organizations (**“FROs”**), connecting FROs to community members, and acting as an accessibility consultant to assist other organizations in designing and implementing accessibility plans and learning opportunities
* connect with other individuals and organizations to collaborate and build capacity for achieving the purpose of the Accessible Canada Act which is to create a “Canada without barriers” by 2040.

In order to facilitate these meaningful partnerships, the Platform enrollment process will ask you to answer a series of questions to set up your account, which may include information about your experience of being a person with a disability, a Deaf person, or a supporter, and your personal background.

The Platform uses this information internally to create or optimize matches between FROs, community organizations and individuals who can provide Services. Only your contact information and information about any specific support needs to enable your participation in the consultation is disclosed by the Platform to FROs to initiate the Services, once you agree to participate. FROs will not receive any personal information from the Platform about an individual until the individual consents to the match made with the FRO.

Our Platform also allows individuals to provide payment information in order to make and accept payments (**“Payments”**) related to Services. IRIS uses third party service providers to process Payments from FROs for Services which individuals or community organizations provide.

## COLLECTION AND USE OF PERSONAL INFORMATION

**To make The Accessibility Exchange work, it needs to get and use information about you. Four kinds of information are collected: personal contact information, personal support needs information, website use information, and demographic information.**

The types of personal information we collect will depend on how you use the Platform. The personal information we collect may include:

**“Personal Contact Information”** – this information includes:

* ***Contact information***: such as full name, mailing address, email address, telephone number, social media links
* ***Payment information***: such as your preferred form of payment
* ***Communications and consultation preference information***: such as your settings related to how you can be contacted on the Platform, the groups you connect to, and the types of meeting you attended (in-person, virtual phone).
* ***Engagement information***: such as information related to the kinds of consultations you wish to participate in (e.g., on transportation barriers, or workplace barriers), the format of those consultations (e.g. survey, focus group) and those you are participating in

**“Personal Support Needs Information”** such as the accessibility features you need to have enabled so you can participate in consultations, for example, ASL/LSQ, communication assistance, personal assistance for in-person consultations, etc..

**“Website Use Information”** – see section below on “INFORMATION AUTOMATICALLY COLLECTED.” This is information that is automatically collected when you use the website, including, your IP (internet protocol) address, your preferences when using the site so that we can remember and authenticate you (e.g. language preference), and how you use the platform so we can improve users’ experiences. For more information see the section below, or contact us at <:email_privacy>.

**“Demographic Information”** such as the disabilities you identify with, year  of birth, gender, race, ethnicity, languages spoken, and other related information. This information is stored in the database of the platform and encrypted so that no one can access the information, including IRIS.

This information is used for two purposes:

* ***Matching***: To match individual consultants to consultations being requested by FROs. This is to ensure there is a diversity in participants for consultations, or for responding to requests for consultations with particular disability groups for example
* ***Identifying the types of users*** on the site – for example, the number of people in a certain province or city, the percentage who identify with different disability experiences, gender identity, etc. No personal identifying information is available to IRIS or anyone else.

We may collect other information that you choose to provide to us, or that we collect with your consent.

We use personal information to provide the Platform. This includes:

* To set up your account, establish, and maintain a relationship with you;
* To verify your identity or contact you;
* To make matches between individuals who can provide Services and FROs;
* To process payments related to Services;
* To analyze the Platform to better understand users of the Platform and enhance our product and service offerings, including by using personal information to anonymize it so that there is no risk of re-identification of that information;
* To connect with you if you have an inquiry;
* To monitor, protect and secure the Platform and our systems, including against fraud;
* To meet audit, legal, and regulatory processes, and requirements; and
* Otherwise with your consent or where permitted by law.

With your consent, we may publish your feedback on our Website or other promotional materials.

**We may also send you email updates about the Website but you can tell us to stop sending emails at any time.**

We may use your **Personal Contact Information** to send you newsletters and other communications about the Platform. You can opt-out of receiving promotional communications from us by following the opt-out or “unsubscribe” instructions provided in your email, or by contacting us as set out below. Please note that upon unsubscribing, you will continue to receive transactional and account-related electronic messages from us.

## DISCLOSURE OF PERSONAL INFORMATION

**To make the Website work we need to share your Personal Contact and Support Needs Information with specific people. This includes people who help us run the Website, people who are looking for help with accessibility plans, and people the law requires us to send information to. We will tell you before we share information with other types of people, and will only share it you agree to have it shared.**

**Service Providers**: In connection with our Platform, we may transfer (or otherwise make available) personal information to third parties who provide services on our behalf.  For example, we may use service providers to host our Platform, store data and provide back-up Platform (including cloud-based service providers), process payments, or provide data analytics. Personal information may be maintained and processed by us or our third party service providers in Canada or in the United States. Our service providers are given the information they need to perform their designated functions, and we do not authorize them to use or disclose personal information for their own marketing or other purposes. Service providers may be authorized to used personal information to anonymize it on our behalf for their subsequent use of that anonymized information. For more information about the way in which our service providers treat personal information, please contact us as set out in the “Contact Us” section below.

**FROs**: If you participate in Services as a consultant, once a potential match has been made to an FRO or other organization, we will ask for your consent before disclosing your Personal Contact or Personal Support Needs Information to the organization. If applicable, your demographic and matching information will be shared with the  organization in a de-identified form – so that, for example, an FRO can understand the range of disabilities participants identify with, or other descriptions of the group of consultants as a whole. Once your Personal Contact or Support Needs Information is disclosed to the FRO or other organization, they are responsible for how they use that information. Please contact the FRO or other organization if you have any questions about their use of your personal information.

**Business Transactions**:  We may transfer personal information as an asset in connection with a prospective or completed merger, acquisition or sale (including transfers made as part of insolvency or bankruptcy proceeding) involving all or part of the Platform or as part of a corporate reorganization or other change in corporate control.

**Legal**: The Platform and its service providers may disclose your personal contact information in response to a search warrant or other legally valid inquiry or order (which may include lawful access by Canadian or other foreign governmental authorities, courts or law enforcement agencies), to other organizations in the case of investigating a breach of an agreement or contravention of law or detecting, suppressing or preventing fraud, or as otherwise required or permitted by applicable Canadian or other law.

## INFORMATION COLLECTED AUTOMATICALLY

**We collect most of the information about you directly from you. Some information is collected automatically when you use the Website. You may have the option to control what information is collected automatically using the settings of your web browser. If you have questions about what information is collected automatically please email <:email_privacy>**

Visiting our Website:  We collect the IP (Internet protocol) addresses of all visitors to our website and other related information such as page requests, browser type, operating system and average time spent on our website. We use this information to help us understand our website activity and to improve our Website.

Cookies:  Our website uses a technology called "cookies". A cookie is a tiny element of data that our website sends to a user’s browser, which may then be stored on the user’s hard drive so that we can recognize the user when they return.  We use cookies to remember your preferences and to authenticate you. You may set your browser to notify you when you receive a cookie or to not accept certain cookies. However, if you decide not to accept cookies from our Website, you may not be able to take advantage of all of the Website features.

Analytics: We may use third party service providers like Fathom Analytics to collect information about your use of the Platform, such as the features used and time spent on the Platform, to help us better understand our users and improve our Platform. The information we gather is used in an aggregate, non-identifiable form. For information about how information is processed in connection with Fathom Analytics please read the Fathom Analytics Policy here.

## THIRD PARTY SITES

**If you click a link to another website, we do not control how information about you on that website will be handled. You should read the privacy policy of every website you visit.**

**Third Party Links**: Our Platform may contain links to other websites that Platform does not own or operate. We provide links to third party websites as a convenience to the user.  These links are not intended as an endorsement of or referral to the linked websites.  The linked websites have separate and independent privacy policies, notices, and terms of use.  We do not have any control over such websites, and therefore we have no responsibility or liability for the manner in which the organizations that operate such linked websites may collect, use or disclose, secure and otherwise treat personal information. We encourage you to read the privacy policy of every website you visit.

**Social Media**: We may offer you the opportunity to engage with Platform content on or through third-party social networking websites, plug-ins and applications. When you engage with us or our content on or through third-party social networking websites, plug-ins and applications, or if you provide links to your social media accounts on your Platform account, you may allow us to have access to certain information associated with your social media account (e.g., name, username, email address, profile picture, gender). We may use this information to personalize your experience on the Platform, including by posting your username on your profile page on the Platform and on the third-party social networking websites, plug-ins and applications, and to provide you with other information you may request.

## SECURITY OF PERSONAL INFORMATION

**We protect information about you using physical tools and digital tools. We also make our staff follow rules to protect information about you. We cannot promise this protection is perfect.**

We have implemented reasonable administrative, technical, and physical safeguards in an effort to protect against unauthorized access, use, modification and disclosure of personal information in our custody and control. Wherever possible, we use industry-standard encryption techniques to secure our databases. We limit access to personal information to our employees and service providers who require access in connection with their role or function.  However, please note that no security measures can offer absolute security and we cannot guarantee that your personal information will not be stolen or accessed without authorization.

## RETENTION OF PERSONAL INFORMATION

**We get rid of information about you when we no longer need to keep it.**

Personal information is maintained on our servers or those of our service providers and is accessible by authorized employees, representatives and agents who require access for the purposes identified in this Privacy Policy. We have personal information retention processes designed to retain personal information for no longer than necessary for the purposes for which such information was provided or to otherwise meet legal requirements.

## YOUR RIGHTS

**You can ask us to see the information about you that we have by emailing us at <:email_privacy>. You can also ask us to stop using information about you at any time. Please ask us if you have any questions about how we use information about you.**

You may request access, updating or correction of your personal information (subject to limited exceptions prescribed by law) by submitting a written request to the Platform’s Privacy Officer (see “Contact Us” below). You may also have the right, in specified circumstances depending on your jurisdiction, to object to our use of your personal information, to request the deletion of your personal information or restrict its use, to request a copy of the information you have provided to us be transferred to another person. You may also (subject to contractual and legal restrictions) refuse to provide your consent, or choose to withdraw your consent, to our processing of your personal information by contacting us as described below. Note that if you refuse to consent, or withdraw your consent, to certain processing of your personal information, we may not be able to provide certain of our services.

If you have any questions about these rights, or you would like to exercise any of them, please contact us as described below. We may request certain personal information for the purposes of verifying your identity.

## UPDATES TO OUR PRIVACY POLICY

**If we make changes to this privacy policy we will let you know on the Website.**

This Privacy Policy may be updated periodically to reflect changes to our personal information practices. The revised Privacy Policy will be posted on our website. We strongly encourage you to please refer to this Privacy Policy often for the latest information about our personal information practices.

The user acknowledges that a French version of these Terms and related documents have been provided to the user and that the user has opted to be bound by the English version.

L’utilisateur reconnaît avoir reçu une version française des présentes modalités et des documents y afférant et avoir choisi d’être lié par la version anglaise.

## CONTACT US

Please contact our Privacy Officer at **<:email_privacy>** if you have any questions, comments or complaints about this Privacy Policy or the personal information practices of us or our service providers.
                ',
                'content->fr' => '
**Le Connecteur pour l’accessibilité est un site Internet géré par l’Institut de recherche et développement sur l’inclusion et la société (également appelé l’IRIS). L’IRIS est responsable des informations vous concernant que vous fournissez au Connecteur pour l’accessibilité. Ce document explique comment l’IRIS traite les informations vous concernant. Si vous avez des questions concernant ce document, veuillez envoyer un courriel à <:email_privacy>.**

L’Institut de recherche et développement sur l’inclusion et la société (« IRIS », « nous », « notre » ou « nos ») prend très au sérieux la confidentialité des renseignements personnels. La présente politique de confidentialité s’applique et décrit la manière dont nous recueillons, utilisons, divulguons et traitons les renseignements personnels dans le cadre de la fourniture du Connecteur pour l’accessibilité (la « Plateforme »), qui comprend le portail Internet situé à l’adresse [<:home>].

{.toc .ignore-counter}
## Table des matières

1. [À PROPOS DE NOTRE PLATEFORME](#à-propos-de-notre-plateforme)
2. [COLLECTE ET UTILISATION DES INFORMATIONS PERSONNELLES](#collecte-et-utilisation-des-informations-personnelles)
3. [DIVULGATION DE RENSEIGNEMENTS PERSONNELS](#divulgation-de-renseignements-personnels)
4. [INFORMATIONS COLLECTÉES AUTOMATIQUEMENT](#informations-collectées-automatiquement)
5. [SITES TIERS](#sites-tiers)
6. [SÉCURITÉ DES RENSEIGNEMENTS PERSONNELS](#sécurité-des-renseignements-personnels)
7. [CONSERVATION DES RENSEIGNEMENTS PERSONNELS](#conservation-des-renseignements-personnels)
8. [VOS DROITS](#vos-droits)
9. [MISES À JOUR DE NOTRE POLITIQUE DE CONFIDENTIALITÉ](#mises-à-jour-de-notre-politique-de-confidentialité)
10. [CONTACTEZ-NOUS](#contactez-nous)

## À PROPOS DE NOTRE PLATEFORME

Le Connecteur pour l’accessibilité est une plateforme en ligne qui rassemble les gens pour :

* élaborer des plans sur l’accessibilité fiables et efficaces,
* partager des ressources d’information,
* s’informer sur l’accessibilité grâce à des formations et des cours en ligne,
* élaborer des solutions innovantes pour surmonter les obstacles liés à l’accessibilité,
* et suivre les progrès accomplis dans la construction d’un Canada accessible et inclusif.

La Plateforme permet aux personnes en situation de handicap et aux personnes sourdes (y compris les personnes autochtones, racisées, noires, LGBTQIA2S+ et d’autres identités), ainsi qu’à leurs proches et sympathisants et aux organisations communautaires,

* d’avoir accès à certains services de consultation (**« services »**), tels que la consultation sur les plans sur l’accessibilité des organisations sous réglementation fédérale , la mise en relation des organisations sous réglementation fédérale avec les membres de la communauté, et le rôle de personne consultante en matière d’accessibilité pour aider d’autres organisations à concevoir et à mettre en œuvre des plans d’accessibilité et des modules d’apprentissage.
* D’entrer en relation avec d’autres personnes et organisations pour collaborer et renforcer les capacités afin d’atteindre l’objectif de la Loi Canadienne sur l’accessibilité qui est de créer un « Canada exempt d’obstacles » d’ici à 2040.

Afin de faciliter ces partenariats fructueux, le processus d’inscription à la Plateforme vous demandera de répondre à une série de questions pour configurer votre compte, qui peuvent inclure des informations sur votre expérience en tant que personne en situation de handicap, personne sourde ou sympathisant, ainsi que sur vos antécédents personnels.

La Plateforme utilise ces informations afin de créer ou d’optimiser les appariements entre les organisations sous réglementation fédérale, les organisations communautaires et les personnes susceptibles de fournir des services. Seules vos coordonnées et les informations relatives à tout besoin de soutien spécifique pour vous permettre de participer à des consultations sont divulguées par la Plateforme aux organisations sous réglementation fédérale, une fois que vous avez accepté d’y participer. Les organisations sous réglementation fédérale ne recevront pas d’informations personnelles de la part de la Plateforme concernant une personne tant que celle-ci n’aura pas consenti à la mise en relation avec l’organisation sous réglementation fédérale.

Notre Plateforme permet également aux individus de fournir des informations de paiement afin d’effectuer et d’accepter des paiements (**« Paiements »**) liés aux services. L’IRIS fait appel à des prestataires de services tiers pour traiter les paiements effectués par les organisations sous réglementation fédérale pour les services fournis par des particuliers ou des organisations communautaires.

## COLLECTE ET UTILISATION DES INFORMATIONS PERSONNELLES

**Pour que le Connecteur pour l’accessibilité fonctionne, il doit obtenir et utiliser des informations qui vous concernent. Quatre types de renseignements sont collectés : les coordonnées personnelles, les besoins d’assistance personnelle, l’utilisation du site Internet et les données démographiques.**

Les types de renseignements personnels que nous recueillons dépendent de la manière dont vous utilisez la Plateforme.

Les renseignements personnels que nous recueillons peuvent inclure :

**« Informations de contact personnelles »** - ces informations comprennent :

* **Vos coordonnées** : nom complet, adresse postale, adresse électronique, numéro de téléphone, liens vers les médias sociaux, etc.
* **Des informations relatives au paiement** : telles que votre mode de paiement préféré
* **Des informations sur les préférences en matière de communication et de consultation** : tel que vos paramètres relatifs à la manière dont vous pouvez être contacté sur la Plateforme, les groupes auxquels vous êtes affilié, ainsi que les types de réunions auxquelles vous avez pris part (en personne ou par téléconférence).
* **Des informations sur la participation** : informations relatives aux types de consultations auxquelles vous souhaitez participer (par exemple, sur les obstacles liés au transport ou au lieu de travail), au format de ces consultations (par exemple, sondage, groupe de discussion) et à celles auxquelles vous participez.

**« Informations sur les besoins d’assistance personnelle »,** telles que les fonctions d’accessibilité que vous devez activer pour pouvoir participer aux consultations, par exemple, ASL/LSQ, assistance à la communication, assistance personnelle pour les consultations en personne, etc.

**« Informations relatives à l’utilisation du site Internet »** voir la section ci-dessous intitulée « INFORMATIONS RECUEILLIES AUTOMATIQUEMENT ». Il s’agit d’informations recueillies automatiquement lorsque vous utilisez le site Internet, notamment votre adresse IP (protocole internet), vos préférences lors de l’utilisation du site afin que nous puissions nous souvenir de vous et vous authentifier (par exemple, préférence linguistique), et la manière dont vous utilisez la Plateforme afin que nous puissions améliorer l’expérience des utilisateurs. Pour plus d’informations, consultez la section ci-dessous ou contactez-nous à l’adresse <:email_privacy>.

**« Informations démographiques »,** telles que les handicaps auxquels vous vous identifiez, votre année de naissance, votre genre, votre couleur de peau, votre origine ethnique, les langues que vous parlez et d’autres informations connexes. Ces informations sont stockées dans la base de données de la Plateforme et chiffrées afin que personne ne puisse y accéder, y compris l’IRIS.

Ces informations sont utilisées à deux fins:

* **Appariement** : Apparier les personnes consultantes aux consultations organisées par les organisations sous réglementation fédérale. Il s’agit de garantir la diversité des personnes participantes aux consultations ou de répondre aux besoins de consultation de certains groupes de personnes en situation de handicap, par exemple.
* **Identifier les types d’utilisateurs** du site - par exemple, le nombre de personnes dans une certaine province ou ville, le pourcentage de personnes qui s’identifient à différentes expériences de handicap, l’identité de genre, etc. Aucune information d’identification personnelle n’est mise à la disposition de l’IRIS ou de qui que ce soit d’autre.

Nous pouvons recueillir d’autres informations que vous choisissez de nous fournir ou que nous recueillons avec votre consentement.

Nous utilisons des informations personnelles pour fournir la Plateforme, par exemple pour:

* Créer votre compte, établir et maintenir une relation avec vous ;
* Vérifier votre identité ou vous contacter ;
* Mettre en relation les personnes susceptibles de fournir des services et les organismes de recherche et de développement ;
* Traiter les paiements liés aux services ;
* Analyser la Plateforme afin de mieux comprendre les utilisateurs de la Plateforme et améliorer nos offres de produits et de services, y compris en utilisant des informations personnelles pour les rendre anonymes de sorte qu’il n’y ait aucun risque de ré-identification de ces informations ;
* Vous contacter si vous avez une question à poser ;
* Contrôler, protéger et sécuriser la Plateforme et nos systèmes, y compris contre la fraude ;
* Respecter les processus et les exigences en matière d’audit, de législation et de réglementation ; et
* D’autres raisons, avec votre consentement ou lorsque la loi le permet.

Avec votre consentement, nous pouvons publier vos commentaires sur notre site Internet ou sur d’autres supports promotionnels.

**Nous pouvons également vous envoyer par courrier électronique des mises à jour concernant le site Internet, mais vous pouvez nous demander d’arrêter l’envoi de courriers électroniques à tout moment.**

Nous pouvons utiliser vos **coordonnées** personnelles pour vous envoyer des bulletins d’information et d’autres communications sur la Plateforme. Vous pouvez refuser de recevoir des communications promotionnelles de notre part en suivant les instructions de désabonnement fournies dans votre courrier électronique ou en nous contactant comme indiqué ci-dessous. Veuillez noter qu’en vous désinscrivant, vous continuerez à recevoir des messages électroniques transactionnels et relatifs à votre compte.

## DIVULGATION DE RENSEIGNEMENTS PERSONNELS

**Pour faire fonctionner le site Internet, nous devons partager vos coordonnées personnelles et vos informations sur vos besoins de soutien avec des personnes spécifiques. Il s’agit notamment des personnes qui nous aident à gérer le site Internet, des personnes qui cherchent à obtenir de l’aide pour les plans sur l’accessibilité et des personnes à qui la loi nous oblige à envoyer des informations. Nous vous informerons avant de partager des informations avec d’autres types de personnes et nous ne les partagerons qu’avec votre accord.**

**Prestataires de services** : Dans le cadre de notre Plateforme, nous pouvons transférer (ou mettre à disposition d’une autre manière) des renseignements personnels à des tiers qui fournissent des services en notre nom. Par exemple, nous pouvons faire appel à des prestataires de services pour héberger notre Plateforme, stocker des données et fournir une plateforme de sauvegarde (y compris des prestataires de services basés dans le nuage), traiter des paiements ou fournir des analyses de données. Les renseignements personnels peuvent être conservés et traités par nous ou par nos fournisseurs de services tiers au Canada ou aux États-Unis. Nos fournisseurs de services reçoivent les informations dont ils ont besoin pour remplir les fonctions qui leur sont attribuées, et nous ne les autorisons pas à utiliser ou à divulguer des renseignements personnels à leurs propres fins de marketing ou autres. Les prestataires de services peuvent être autorisés à utiliser des renseignements personnels pour les rendre anonymes en notre nom, en vue de leur utilisation ultérieure de ces informations anonymes. Pour plus d’informations sur la manière dont nos fournisseurs de services traitent les renseignements personnels, veuillez nous contacter comme indiqué dans la section « Nous contacter » ci-dessous.

**Organisations sous réglementation fédérale** : Si vous participez aux services en tant que personne consultante, une fois qu’un appariement potentiel a été établi avec une organisation sous réglementation fédérale ou une autre organisation, nous vous demanderons votre consentement avant de divulguer vos coordonnées personnelles ou les renseignements sur vos besoins de soutien personnel à l’organisation. Le cas échéant, vos données démographiques et d’appariement seront communiquées à l’organisation sous une forme dépersonnalisée - de sorte que, par exemple, une organisation sous réglementation fédérale puisse comprendre l’éventail des handicaps auxquels les personnes participantes s’identifient, ou d’autres descriptions du groupe de personnes consultantes dans son ensemble. Une fois que vos coordonnées personnelles ou vos informations sur vos besoins de soutien sont divulguées à l’organisation sous réglementation fédérale ou à une autre organisation, ces dernières sont responsables de l’utilisation quelles font de ces informations. Veuillez contacter l’organisation sous réglementation fédérale ou l’autre organisation si vous avez des questions sur l’utilisation qu’elles font de vos renseignements personnels.

**Transactions commerciales** :  Nous pouvons transférer des renseignements personnels en tant qu’actif dans le cadre d’une fusion, d’une acquisition ou d’une vente (y compris les transferts effectués dans le cadre d’une procédure d’insolvabilité ou de faillite), envisagée ou réalisée, impliquant tout ou partie de la Plateforme, ou dans le cadre d’une réorganisation d’entreprise ou d’un autre changement de contrôle de l’entreprise.

**Législation** : La Plateforme et ses fournisseurs de services peuvent divulguer vos coordonnées personnelles en réponse à un mandat de perquisition ou à toute autre demande ou ordonnance légalement valide (ce qui peut inclure l’accès légal par les autorités gouvernementales canadiennes ou étrangères, les tribunaux ou les organismes d’application de la loi), à d’autres organisations dans le cas d’une enquête sur la violation d’un accord ou d’une infraction à la loi ou pour détecter, supprimer ou prévenir une fraude, ou si cela est autrement requis ou autorisé par la loi canadienne ou toute autre loi applicable.

## INFORMATIONS COLLECTÉES AUTOMATIQUEMENT

**Nous recueillons la plupart des informations vous concernant directement auprès de vous. Certaines informations sont collectées automatiquement lorsque vous utilisez le site Internet. Vous pouvez avoir la possibilité de contrôler les informations collectées automatiquement en utilisant les paramètres de votre navigateur Internet. Si vous avez des questions sur les informations collectées automatiquement, veuillez envoyer un courriel à <:email_privacy>.**

**Visite de notre site Internet** :  Nous collectons les adresses IP (protocole Internet) de tous les visiteurs de notre site Internet et d’autres informations connexes telles que les pages demandées, le type de navigateur, le système d’exploitation et le temps moyen passé sur notre site Internet. Nous utilisons ces informations pour nous aider à comprendre l’activité de notre site Internet et pour l’améliorer.

**Témoins (cookies)** :  Notre site Internet utilise une technologie appelée  « cookies ». Un cookie est un minuscule élément de données que notre site Internet envoie au navigateur d’un utilisateur et qui peut ensuite être stocké sur le disque dur de l’utilisateur afin que nous puissions reconnaître l’utilisateur lorsqu’il revient sur le site.  Nous utilisons les cookies pour mémoriser vos préférences et vous authentifier. Vous pouvez configurer votre navigateur pour qu’il vous avertisse lorsque vous recevez un cookie ou pour qu’il n’accepte pas certains cookies. Toutefois, si vous décidez de ne pas accepter les cookies de notre site Internet, il se peut que vous ne puissiez pas profiter de toutes les fonctionnalités du site Internet.

**Analyses** : Nous pouvons faire appel à des fournisseurs de services tiers tels que Fathom Analytics pour collecter des informations sur votre utilisation de la plateforme, telles que les fonctionnalités utilisées et le temps passé sur la plateforme, afin de nous aider à mieux comprendre nos utilisateurs et à améliorer notre plateforme. Les informations que nous recueillons sont utilisées sous une forme agrégée et non identifiable. Pour plus d’informations sur la manière dont les informations sont traitées dans le cadre de Fathom Analytics, veuillez consulter la politique de Fathom Analytics ici.

## SITES TIERS

**Si vous cliquez sur un lien vers un autre site Internet, nous ne contrôlons pas la manière dont les informations vous concernant sur ce site seront traitées. Nous vous conseillons de lire la politique de confidentialité de chaque site Internet que vous visitez.**

**Liens avec des tiers** :  Notre plateforme peut contenir des liens vers d’autres sites Internet que la plateforme ne possède pas ou n’exploite pas. Nous fournissons des liens vers des sites Internet de tiers pour la commodité de l’utilisateur. Ces liens n’ont pas pour but d’approuver les sites Internet liés ou de s’y référer. Les sites Internet liés ont des politiques de confidentialité, des avis et des conditions d’utilisation distincts et indépendants. Nous n’avons aucun contrôle sur ces sites Internet et, par conséquent, nous ne sommes pas responsables de la manière dont les organisations qui exploitent ces sites Internet liés peuvent collecter, utiliser ou divulguer, sécuriser et traiter de toute autre manière les renseignements personnels. Nous vous encourageons à lire la politique de confidentialité de chaque site Internet que vous visitez.

**Médias sociaux** : Nous pouvons vous offrir la possibilité d’interagir avec le contenu de la Plateforme sur ou par le biais Internet de réseaux sociaux, de plugiciels et d’applications de tiers. Lorsque vous vous interagissez avec nous ou avec notre contenu sur ou par l’intermédiaire Internet de réseaux sociaux tiers, de plugiciels et d’applications, ou si vous fournissez des liens vers vos comptes de médias sociaux sur votre compte de la Plateforme, vous pouvez nous permettre d’accéder à certaines informations associées à votre compte de réseaux sociaux (par exemple, nom, nom d’utilisateur, adresse électronique, photo de profil, genre). Nous pouvons utiliser ces informations pour personnaliser votre expérience sur la Plateforme, y compris en affichant votre nom d’utilisateur sur votre page de profil sur la Plateforme et sur les sites Internet de réseaux sociaux tiers, les plugiciels et les applications, et pour vous fournir d’autres informations que vous pourriez demander.

## SÉCURITÉ DES RENSEIGNEMENTS PERSONNELS

**Nous protégeons les informations vous concernant à l’aide d’outils physiques et numériques. Nous demandons également à notre personnel de suivre des règles pour protéger les informations vous concernant. Nous ne pouvons pas promettre que cette protection est parfaite.**

Nous avons mis en place des mesures de protection administratives, techniques et physiques raisonnables afin d’empêcher l’accès, l’utilisation, la modification et la divulgation non autorisés des renseignements personnels dont nous avons la garde et le contrôle. Dans la mesure du possible, nous utilisons des techniques de chiffrage standard pour sécuriser nos bases de données. Nous limitons l’accès aux renseignements personnels à nos employés et à nos prestataires de services qui en ont besoin dans le cadre de leur rôle ou de leur fonction.  Toutefois, veuillez noter qu’aucune mesure de sécurité ne peut offrir une sécurité absolue et que nous ne pouvons pas garantir que vos renseignements personnels ne seront pas volés ou consultés sans autorisation.

## CONSERVATION DES RENSEIGNEMENTS PERSONNELS

**Nous supprimons les informations vous concernant lorsque nous n’avons plus besoin de les conserver.**

Les renseignements personnels sont conservés sur nos serveurs ou ceux de nos prestataires de services et sont accessibles aux employés, représentants et agents autorisés qui ont besoin d’y accéder aux fins indiquées dans la présente politique de protection de la vie privée. Nous disposons de procédures de conservation des renseignements personnels conçus pour ne pas les conserver plus longtemps que nécessaires aux fins pour lesquels ils ont été fournis ou pour répondre à des exigences légales.

## VOS DROITS

**Vous pouvez nous demander de consulter les informations que nous détenons sur vous en nous envoyant un courrier électronique à l’adresse <:email_privacy>. Vous pouvez également nous demander de cesser d’utiliser les informations vous concernant à tout moment. N’hésitez pas à nous contacter si vous avez des questions sur la manière dont nous utilisons les informations vous concernant.**

Vous pouvez demander l’accès, la mise à jour ou la correction de vos renseignements personnels (sous réserve d’exceptions limitées prescrites par la loi) en soumettant une demande écrite au responsable de la protection de la vie privée de la Plateforme (voir « Nous contacter » ci-dessous). Vous pouvez également avoir le droit, dans des circonstances précises dépendant de votre juridiction, de vous opposer à notre utilisation de vos renseignements personnels, de demander la suppression de vos renseignements personnels ou d’en restreindre l’utilisation, de demander qu’une copie des informations que vous nous avez fournies soit transférée à une autre personne. Vous pouvez également (sous réserve de restrictions contractuelles et légales) refuser de donner votre consentement ou choisir de retirer votre consentement au traitement de vos renseignements personnels en nous contactant de la manière décrite ci-dessous. Notez que si vous refusez de consentir, ou si vous retirez votre consentement, à certains traitements de vos renseignements personnels, il se peut que nous ne soyons pas en mesure de fournir certains de nos services.

Si vous avez des questions sur ces droits ou si vous souhaitez exercer l’un d’entre eux, veuillez nous contacter de la manière décrite ci-dessous. Nous pouvons vous demander certains renseignements personnels afin de vérifier votre identité.

## MISES À JOUR DE NOTRE POLITIQUE DE CONFIDENTIALITÉ

**Si nous apportons des modifications à cette politique de confidentialité, nous vous en informerons sur le site Internet.**

La présente politique de confidentialité peut être mise à jour périodiquement pour refléter les changements apportés à nos pratiques en matière de renseignements personnels. La version révisée de la politique de protection de la vie privée sera publiée sur notre site Internet. Nous vous encourageons vivement à consulter régulièrement cette politique de confidentialité pour obtenir les informations les plus récentes sur nos pratiques en matière de renseignements personnels.

## CONTACTEZ-NOUS

Veuillez contacter notre responsable de la protection de la vie privée à l’adresse **<:email_privacy>** si vous avez des questions, des commentaires ou des plaintes concernant la présente politique de protection de la vie privée ou nos pratiques en matière de renseignements personnels ou celles de nos fournisseurs de services.
                ',
            ]
        );
    }
}
