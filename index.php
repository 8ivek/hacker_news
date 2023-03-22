<?php

header('Content-Type: application/rss+xml; charset=utf-8');

$domain = "https://hacker-news.firebaseio.com/v0/";

// top stories
$top_stories_url = $domain . "topstories.json?print=pretty";
$top_stories_json = file_get_contents($top_stories_url);
$top_story_ids = json_decode($top_stories_json);

if (!is_array($top_story_ids)) {
    echo "top stories not available! connection issue?";
}

$rss_item = '';
for ($i = 0; $i < 5; $i++) {
    $story_id = $top_story_ids[$i];
    if ($story_id < 0) {
        echo "\ninvalid story id" . $story_id;
        continue;
    }
    $story_url = $domain . "item/$story_id.json?print=pretty";
    $story_json = file_get_contents($story_url);
    $top_story_details = json_decode($story_json);

    if (!is_array($top_story_ids)) {
        echo "\ntop stories not available! connection issue?";
        continue;
    }
    $rss_item .= "\n<item>
    <title>" . $top_story_details->title . "</title>
    <link>" . $top_story_details->url . "</link>
    <description>" . 'type: ' . $top_story_details->type . ', score: ' . $top_story_details->score . "</description>
    <dc:creator>" . $top_story_details->by . "</dc:creator>
    <category>" . $top_story_details->type . "</category>
    <guid isPermaLink='false'>" . $top_story_details->id . "</guid>
    <pubDate>" . date(DateTime::RFC822, $top_story_details->time) . "</pubDate>
  </item>";
}

$rss = '<?xml version="1.0" encoding="UTF-8" ?>
<rss version="2.0">

<channel>
  <title>RSS for top 5 hacker news</title>
  <link>https://news.ycombinator.com</link>
  <description>RSS for top 5 hacker news</description>
  ' . $rss_item . '
  </channel>
</rss>';
echo $rss;