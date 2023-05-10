<?php
$capabilities = array(
    'block/exaquest:view' => array(
        'captype' => 'read',
        'contextlevel' => CONTEXT_BLOCK,
        'archetypes' => array(
            'manager' => CAP_ALLOW, // every role in exaquest has archetype manager (for now)
            'user' => CAP_PREVENT,
            // add any other roles you want to allow here
        )
    ),

    'block/exaquest:myaddinstance' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes' => array(
            'user' => CAP_ALLOW,
        ),

        'clonepermissionsfrom' => 'moodle/my:manageblocks'
    ),

    'block/exaquest:addinstance' => array(
        'riskbitmask' => RISK_SPAM | RISK_XSS,

        'captype' => 'write',
        'contextlevel' => CONTEXT_BLOCK,
        'archetypes' => array(
            'editingteacher' => CAP_ALLOW,
            'manager' => CAP_ALLOW
        ),

        'clonepermissionsfrom' => 'moodle/site:manageblocks'
    ),

    // Roles are written in the German name. Capabilities in english.
    // The capabilities are created and assigned upon installing the block
    'block/exaquest:fragenersteller' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_COURSE,
    ),
    'block/exaquest:modulverantwortlicher' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_COURSE,
    ),
    'block/exaquest:admintechnpruefungsdurchf' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_COURSE,
    ),
    'block/exaquest:pruefungskoordination' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_COURSE,
    ),
    'block/exaquest:pruefungsstudmis' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_COURSE,
    ),
    'block/exaquest:fachlfragenreviewer' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_COURSE,
    ),
    'block/exaquest:beurteilungsmitwirkende' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_COURSE,
    ),
    'block/exaquest:fachlicherpruefer' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_COURSE,
    ),
    'block/exaquest:pruefungsmitwirkende' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_COURSE,
    ),
    'block/exaquest:fachlicherzweitpruefer' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_COURSE,
    ),
    'block/exaquest:fragenerstellerlight' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_COURSE,
    ),
    'block/exaquest:fachlfragenreviewerlight' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_COURSE,
    ),

    // capabilities defined by ZML:
    'block/exaquest:readallquestions' => array(
        'captype' => 'read',
        'contextlevel' => CONTEXT_COURSE,
    ),
    'block/exaquest:readquestionstatistics' => array(
        'captype' => 'read',
        'contextlevel' => CONTEXT_COURSE,
    ),
    'block/exaquest:changestatusofreleasedquestions' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_COURSE,
    ),
    'block/exaquest:createquestion' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_COURSE,
    ),
    'block/exaquest:setstatustoreview' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_COURSE,
    ),
    'block/exaquest:reviseownquestion' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_COURSE,
    ),
    'block/exaquest:setstatustofinalised' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_COURSE,
    ),
    'block/exaquest:viewownrevisedquestions' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_COURSE,
    ),
    'block/exaquest:viewquestionstoreview' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_COURSE,
    ),
    'block/exaquest:editquestiontoreview' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_COURSE,
    ),
    'block/exaquest:viewfinalisedquestions' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_COURSE,
    ),
    'block/exaquest:viewquestionstorevise' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_COURSE,
    ),
    'block/exaquest:releasequestion' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_COURSE,
    ),
    'block/exaquest:editallquestions' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_COURSE,
    ),
    'block/exaquest:addquestiontoexam' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_COURSE,
    ),
    'block/exaquest:releaseexam' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_COURSE,
    ),
    'block/exaquest:doformalreview' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_COURSE,
    ),
    'block/exaquest:dofachlichreview' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_COURSE,
    ),
    'block/exaquest:executeexam' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_COURSE,
    ),
    'block/exaquest:assignsecondexaminator' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_COURSE,
    ),
    'block/exaquest:definequestionblockingtime' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_COURSE,
    ),
    'block/exaquest:viewexamresults' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_COURSE,
    ),
    'block/exaquest:gradeexam' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_COURSE,
    ),
    'block/exaquest:createexamstatistics' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_COURSE,
    ),
    'block/exaquest:viewexamstatistics' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_COURSE,
    ),
    'block/exaquest:correctexam' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_COURSE,
    ),
    'block/exaquest:acknowledgeexamcorrection' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_COURSE,
    ),
    'block/exaquest:releaseexamgrade' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_COURSE,
    ),
    'block/exaquest:releasecommissionalexamgrade' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_COURSE,
    ),
    'block/exaquest:exportgradestokusss' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_COURSE,
    ),
    'block/exaquest:executeexamreview' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_COURSE,
    ),
    'block/exaquest:addparticipanttomodule' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_COURSE,
    ),
    'block/exaquest:assignroles' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_COURSE,
    ),
    'block/exaquest:changerolecapabilities' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_COURSE,
    ),
    'block/exaquest:createroles' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_COURSE,
    ),

    // Defined during development
    'block/exaquest:viewstatistic' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_COURSE,
    ),
    'block/exaquest:viewsimilaritytab' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_COURSE,
    ),
    'block/exaquest:viewexamstab' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_COURSE,
    ),
    'block/exaquest:viewcategorytab' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_COURSE,
    ),
    'block/exaquest:viewdashboardtab' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_COURSE,
    ),
    'block/exaquest:viewquestionbanktab' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_COURSE,
    ),
    'block/exaquest:viewdashboardoutsidecourse' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_SYSTEM,
    ),
    'block/exaquest:viewownquestions' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_COURSE,
    ),
    'block/exaquest:viewquestionstorelease' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_COURSE,
    ),
    'block/exaquest:viewnewexams' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_COURSE,
    ),
    'block/exaquest:viewcreatedexams' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_COURSE,
    ),
    'block/exaquest:viewreleasedexams' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_COURSE,
    ),
    'block/exaquest:viewactiveexams' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_COURSE,
    ),
    'block/exaquest:viewfinishedexams' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_COURSE,
    ),
    'block/exaquest:viewgradesreleasedexams' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_COURSE,
    ),
    'block/exaquest:requestnewexam' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_COURSE,
    ),
    'block/exaquest:createexam' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_COURSE,
    ),
    'block/exaquest:exaquestuser' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_COURSE,
    ),
    'block/exaquest:viewquestionstorevise' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_COURSE,
    ),
    'block/exaquest:assignaddquestions' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_COURSE,
    ),
);