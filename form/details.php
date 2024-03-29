<?php
namespace local_custom_certification\form;

if (!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is forbidden.');    ///  It must be included from a Moodle page
}

class certification_details_form extends \moodleform
{
    function definition()
    {
        $mform =& $this->_form;
        $editoroptions = $this->_customdata['editoroptions'];
        $overviewfilesoptions = $this->_customdata['overviewfilesoptions'];
        $certif = $this->_customdata['params'];
        $action = $this->_customdata['action'];
        $categories = $this->_customdata['categories'];

        $draftitemid = file_get_submitted_draft_itemid('overviewfiles_filemanager');
        file_prepare_draft_area($draftitemid,  \context_system::instance()->id, 'local_custom_certification', 'overviewfiles_filemanager', $certif->id, $overviewfilesoptions);
        $certif->overviewfiles_filemanager = $draftitemid;
        $this->set_data($certif);

        if ($action != 'add') {
            $mform->addElement('hidden', 'id');
            $mform->setType('id', PARAM_INT);
            $mform->setDefault('id', isset($certif->id) ? $certif->id : 0);
        }

        $mform->addElement('hidden', 'edit');
        $mform->setType('edit', PARAM_INT);
        $mform->setDefault('edit', $action == 'details' ? 1 : 0);
        if($certif->id == null){
            $mform->addElement('html', \html_writer::tag('p', get_string('instructions:tabdetails', 'local_custom_certification'), ['class' => 'instructions']));
        }

        $mform->addElement('header', 'programdetails', get_string('programdetails', 'local_custom_certification'));
        $mform->addElement('html', \html_writer::tag('p', get_string('instructions:programdetails', 'local_custom_certification'), ['class' => 'instructions']));

        $mform->addElement('select', 'category', get_string('category', 'local_custom_certification'), $categories);
        $mform->setType('category', PARAM_INT);

        $mform->addRule('category', get_string('missingcategory'), 'required', null, 'client');


        $mform->addElement('text', 'fullname', get_string('fullname', 'local_custom_certification'), 'maxlength="254" size="50"');
        $mform->addRule('fullname', get_string('missingfullname', 'local_custom_certification'), 'required', null, 'client');
        $mform->setType('fullname', PARAM_TEXT);

        $mform->addElement('text', 'shortname', get_string('shortname', 'local_custom_certification'), 'maxlength="100" size="20"');
        $mform->setType('shortname', PARAM_TEXT);
        $mform->addHelpButton('shortname', 'programshortname', 'local_custom_certification');
        $mform->addRule('shortname', get_string('missingshortname', 'local_custom_certification'), 'required', null, 'client');

        $mform->addElement('text', 'idnumber', get_string('idnumberprogram', 'local_custom_certification'), 'maxlength="100"  size="10"');
        $mform->setType('idnumber', PARAM_TEXT);
        $mform->addHelpButton('idnumber', 'programidnumber', 'local_custom_certification');

        $mform->addElement('checkbox', 'visible', get_string('visibleprogram', 'local_custom_certification'));
        $mform->setType('visible', PARAM_INT);
        $mform->addHelpButton('visible', 'programvisible', 'local_custom_certification');

        if (isset($certif->summary)) {
            $mform->addElement('editor', 'summary', get_string('description', 'local_custom_certification'), null, $editoroptions)
                ->setValue(['text' => $certif->summary]);
        } else {
            $mform->addElement('editor', 'summary', get_string('description', 'local_custom_certification'), null, $editoroptions);
        }
        $mform->addHelpButton('summary', 'summary', 'local_custom_certification');
        $mform->setType('summary', PARAM_RAW);

        $mform->addElement('filemanager', 'overviewfiles_filemanager',
            get_string('programoverviewfiles', 'local_custom_certification'), null, $overviewfilesoptions);
        $mform->addHelpButton('overviewfiles_filemanager', 'programoverviewfiles', 'local_custom_certification');

        if (isset($certif->endnote)) {
            $mform->addElement('editor', 'endnote', get_string('endnote', 'local_custom_certification'), null, $editoroptions)
                ->setValue(['text' => $certif->endnote]);
        } else {
            $mform->addElement('editor', 'endnote', get_string('endnote', 'local_custom_certification'), null, $editoroptions);
        }
        $mform->addHelpButton('endnote', 'endnote', 'local_custom_certification');
        $mform->setType('endnote', PARAM_RAW);

        $this->add_action_buttons();
        $mform->closeHeaderBefore('buttonsave');

    }

    function validation($data, $files)
    {
        global $DB;
        $certif = $this->_customdata['params'];
        $errors = [];
        $params = [];
        $params[] = $data['idnumber'];
        $params[] = ($data['edit'] ? $certif->id : 0);
        $params[] = 0;
        if (!empty($data['idnumber']) && $DB->get_record_sql('SELECT * FROM {certif} WHERE idnumber = ? AND id != ? AND deleted = ?', $params)) {
            $errors['idnumber'] = get_string('error:idnumberexists', 'local_custom_certification');
        }
        
        $params = [];
        $params[] = $data['shortname'];
        $params[] = ($data['edit'] ? $certif->id : 0);
        $params[] = 0;
        if (!empty($data['shortname']) && $DB->get_record_sql('SELECT * FROM {certif} WHERE shortname = ? AND id != ? AND deleted = ?', $params)) {
            $errors['shortname'] = get_string('error:shortnameunique', 'local_custom_certification');
        }

        return $errors;
    }

    function set_data($default_values)
    {
        if (is_object($default_values)) {
            $default_values = (array)$default_values;
        }

        parent::set_data($default_values);
    }
}
