<?php

## 需安装scws 1.2.3以及php—scws扩展
## 词典以及规则需自行下载

function scws($str)
{
    $so = scws_new();
    // scws路径和规则
    $so->set_dict(ini_get("scws.default.fpath").'/dict.utf8.xdb');
    $so->add_dict(ini_get("scws.default.fpath").'/dict_user.txt', SCWS_XDICT_TXT);   # dict_user为个人词库
    $so->set_rule(ini_get("scws.default.fpath").'/rules.utf8.ini');
    // 忽略符号
    $so->set_ignore(true);
    $so->send_text($str);
    $result = $so->get_result();

    // 整理词语，保存成数组
    $words = [];
    foreach($result as $word) {
        $words[] = $word['word'];
    }

    return $words;
}
