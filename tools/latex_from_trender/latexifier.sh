#! /usr/bin/sh

/opt/lampp/bin/php latexifier_track_comp_req.php
/opt/lampp/bin/php latexifier_track_class_req.php
/opt/lampp/bin/php latexifier_track_req_class.php
/opt/lampp/bin/php latexifier_track_req_comp.php

/opt/lampp/bin/php latexifier_components.php
/opt/lampp/bin/php latexifier_classes.php
/opt/lampp/bin/php latexifier_re.php
/opt/lampp/bin/php latexifier_uc.php
