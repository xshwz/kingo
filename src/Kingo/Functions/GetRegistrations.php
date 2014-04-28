<?php

class Kingo_Functions_GetRegistrations extends Kingo_Functions {
  public function __invoke() {
    return array(
      'thead' => array(
        '学期学年', '院(系)/部', '年级/专业', '行政班级',
        '学籍状态', '在校状态', '注册状态',
      ),
      'tbody' => $this->parseTable($this->DOM($this->kingo->GET(
          'xsxj/stu_xszcxs_rpt.aspx'))->getElementsByTagName('table')->item(2),
          range(1, 7)
       ),
    );
  }
}
