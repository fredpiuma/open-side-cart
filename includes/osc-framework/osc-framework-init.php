<?php

namespace OpenSideCart\Framework;

const OSC_FW_DIR = __DIR__;
const OSC_FW_VERSION = '2.0.0';


function osc_fw_framework_includes(){
	require_once __DIR__.'/class-osc-fw-helper.php';
	require_once __DIR__.'/class-osc-fw-exception.php';
}

osc_fw_framework_includes();