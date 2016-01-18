<?php
    /**
     * Created by PhpStorm.
     * User: anjan
     * Date: 10/19/15
     * Time: 10:58 AM
     */

    /**
     * get default data for a manga status json file
     *
     * @return array
     */

    function getDefaultMangaStatusData() {

        return array(
            'chapters' => array(
                'total' => 0,
                'allChapters' => array(),
                'completedChapters' => array(),
                'partialChapters' => array(),
            )
        );

    }

    /**
     * Parses and prepares manag satatus data in acceptable format
     *
     * @param array $data
     *
     * @return array
     */

    function parseAndPrepareMangaStatusData($data = array()) {

        if(!is_array($data)) {
            $data = array();
        }

        if(!isset($data['chapters']) || !is_array($data['chapters'])) {

            $data['chapters'] = array();

        }

        $chapters = $data['chapters'];

        $chapters['total'] = isset($chapters['total']) ? (int)$chapters['total'] : 0;

        $chapters['allChapters'] = isset($chapters['allChapters']) && is_array($chapters['allChapters']) ? $chapters['allChapters'] : array();

        $chapters['completedChapters'] = isset($chapters['completedChapters']) && is_array($chapters['completedChapters']) ? $chapters['completedChapters'] : array();

        $chapters['partialChapters'] = isset($chapters['partialChapters']) && is_array($chapters['partialChapters']) ? $chapters['partialChapters'] : array();

        $data['chapters'] = $chapters;

        return $data;
    }

    /**
     * Read and parse managa status data from file
     *
     * @param $filePath
     *
     * @return array|bool
     */

    function getMangaStatusDataFromFile($filePath) {

        $filePath = trim($filePath);

        if(!$filePath) {
            return false;
        }

        if(!file_exists($filePath)) {
            return false;
        }

        $data = json_decode(file_get_contents($filePath),true);

        return parseAndPrepareMangaStatusData($data);

    }

    /**
     * Save manga status data
     *
     * @param       $path
     * @param array $data
     *
     * @return bool|int
     */

    function saveMangaStatusDataToFile($path,$data = array()) {

        $path = trim($path);

        if($path == '') {
            return false;
        }

        return file_put_contents($path,json_encode(parseAndPrepareMangaStatusData($data),JSON_PRETTY_PRINT));

    }

    function getChaptersCount(&$statusData = array()) {

        if(!is_array($statusData)) {
            $statusData = array();
        }

        return (int)__ARRAY_VALUE($statusData,'chapters/total',0);

    }

    /**
     * get all chapters list
     *
     * @param array $statusData
     *
     * @return array|mixed
     */

    function getAllChaptersList(&$statusData = array()) {

        if(!is_array($statusData)) {
            $statusData = array();
        }

        $list = __ARRAY_VALUE($statusData,'chapters/allChapters',array());

        if(!is_array($list)) {
            $list = array();
        }

        return $list;

    }

    /**
     * get completed chapters list
     *
     * @param array $statusData
     *
     * @return array|mixed
     */

    function getCompletedChaptersList(&$statusData = array()) {

        if(!is_array($statusData)) {
            $statusData = array();
        }

        $list = __ARRAY_VALUE($statusData,'chapters/completedChapters',array());

        if(!is_array($list)) {
            $list = array();
        }

        return $list;

    }

    /**
     * get partial chapters list
     *
     * @param array $statusData
     *
     * @return array|mixed
     */

    function getPartialChaptersList(&$statusData = array()) {

        if(!is_array($statusData)) {
            $statusData = array();
        }

        $list = __ARRAY_VALUE($statusData,'chapters/partialChapters',array());

        if(!is_array($list)) {
            $list = array();
        }

        return $list;

    }

    /**
     * Update chapters total count
     *
     * @param string $path
     * @param int    $count
     */

    function updateChaptersTotalCount($path = '',$count = 0) {

        $statusData = getMangaStatusDataFromFile($path);

        if(!$statusData) {
            return;
        }

        $statusData['chapters']['total'] = (int)$count;

        saveMangaStatusDataToFile($path,$statusData);

    }

    /**
     * Upadte all chapters list
     *
     * @param string $path
     * @param array  $list
     */

    function updateAllChaptersList($path = '',$list = array()) {

        $statusData = getMangaStatusDataFromFile($path);

        if(!$statusData) {
            return;
        }

        if(!is_array($list)) {
            $list = array();
        }

        $statusData['chapters']['allChapters'] = $list;

        saveMangaStatusDataToFile($path,$statusData);

    }