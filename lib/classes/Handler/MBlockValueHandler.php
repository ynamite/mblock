<?php
/**
 * @author mail[at]joachim-doerr[dot]com Joachim Doerr
 * @package redaxo5
 * @license MIT
 */

class MBlockValueHandler
{
    /**
     * @return array
     * @author Joachim Doerr
     */
    public static function loadRexVars()
    {
        $sliceId = rex_request('slice_id', 'int', false);
        $result = array();

        if (rex_get('function') == 'add') {
            return $result;
        }

        if ($sliceId != false) {
            $table = rex::getTablePrefix() . 'article_slice';
            $fields = '*';
            $where = 'id="' . $_REQUEST['slice_id'] . '"';

            $sql = rex_sql::factory();
            $query = '
                SELECT ' . $fields . '
                FROM ' . $table . '
                WHERE ' . $where;

            $sql->setQuery($query);
            $rows = $sql->getRows();

            if ($rows > 0) {
                for ($i = 1; $i <= 20; $i++) {
                    $result['value'][$i] = $sql->getValue('value' . $i);

                    if ($i <= 10) {
                        $result['filelist'][$i] = $sql->getValue('medialist' . $i);
                        $result['linklist'][$i] = $sql->getValue('linklist' . $i);
                        $result['file'][$i] = $sql->getValue('media' . $i);
                        $result['link'][$i] = $sql->getValue('link' . $i);
                    }

                    $jsonResult = json_decode(htmlspecialchars_decode($result['value'][$i]),true);

                    if (is_array($jsonResult)) {
                        $result['value'][$i] = $jsonResult;
                    }
                }
            }
        }
        return $result;
    }
}