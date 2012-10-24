<?php
    /**
     * Expected Array format
     * array( 'countryname' => array('gold_medals' => integer, 'total_medals' => integer ))
     */
    $olympicMedals = array();
    $htmlOlympicMedals = file_get_contents('http://espn.go.com/olympics/summer/2012/medals');

    libxml_use_internal_errors(true);
    $domOlympicMedals = new \DOMDocument();
    $domOlympicMedals->loadHTML($htmlOlympicMedals);
    libxml_use_internal_errors(false);

    $domSearch = new \DOMXPath($domOlympicMedals);
    $nodes = $domSearch->query('//ul[@id="filters-top3"]');

    $domMainContent = "";
    foreach($nodes as $node) {
        // Assuming it's the first found node.
        $domMainContent = $node;
        break;
    }
?>
<html>
    <head>
        <title>2012 Olympics Scraper & Google Charts</title>
    </head>
    <body>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
        <script>
            var mainContent = <?php echo json_encode($domMainContent->C14N()) ?>;
            var olympicMedals = [];

            // Add top3 medal data
            $(mainContent).find('ul.medal-bar').each(
                function(index, element) {
                    olympicMedals.push({ 'country': $(element).find('li.country a').text(), 'gold_medals': $(element).find('li.count-g').text(), 'total_medals': $(element).find('li.total').text() });
                }
            );

            // Add remaining medal data
            $(mainContent).find('tr').each(
                function(index, element) {
                    olympicMedals.push({ 'country': $(element).find('td.title a').text(), 'gold_medals': $(element).find('td.gold').text(), 'total_medals': $(element).find('td.total').text() });
                }
            );
        </script>
    </body>
</html>