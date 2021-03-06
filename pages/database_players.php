<?php
/*
 * PLAYERS DATABASE AJAX
 */

// If an explicit return request for the index was provided
if (!empty($_REQUEST['return']) && $_REQUEST['return'] == 'index'){
    // Exit with only the database link markup
    exit($mmrpg_database_players_links);
}



/*
 * PLAYERS DATABASE PAGE
 */

// Define the SEO variables for this page
$this_seo_title = 'Players '.(!empty($this_current_filter) ? '('.$this_current_filter_name.' Type) ' : '').'| Database | '.$this_seo_title;
$this_seo_description = 'The player database contains detailed information about the Mega Man RPG Prototype\'s playable characters including their unlockable abilities, battle quotes, and sprite sheets. The Mega Man RPG Prototype is a browser-based fangame that combines the mechanics of both the Pokémon and Mega Man series of video games into one strange and wonderful little time waster.';

// Define the Open Graph variables for this page
$this_graph_data['title'] = 'Player Database'.(!empty($this_current_filter) ? ' ('.$this_current_filter_name.' Type) ' : '');
$this_graph_data['description'] = 'The player database contains detailed information about the Mega Man RPG Prototype\'s playable characters including their unlockable abilities, battle quotes, and sprite sheets.';
//$this_graph_data['image'] = MMRPG_CONFIG_ROOTURL.'images/assets/mmrpg-prototype-logo.png';
//$this_graph_data['type'] = 'website';

// Define the MARKUP variables for this page
$this_markup_header = 'Mega Man RPG Prototype Player Database';
//$this_markup_counter = '<span class="count count_header">( '.(!empty($mmrpg_database_players_links_counter) ? ($mmrpg_database_players_links_counter == 1 ? '1 Player' : $mmrpg_database_players_links_counter.' Players') : '0 Players').' )</span>';

// If a specific player has NOT been defined, show the quick-switcher
reset($mmrpg_database_players);
if (!empty($this_current_token)){ $first_player_key = $this_current_token; }
else { $first_player_key = key($mmrpg_database_players); }

// Only show the next part of a specific player was requested
if (!empty($this_current_token)){

    // Loop through the player database and display the appropriate data
    $key_counter = 0;
    $this_current_key = false;
    foreach($mmrpg_database_players AS $player_key => $player_info){

        // If a specific player has been requested and it's not this one
        if (!empty($this_current_token) && $this_current_token != $player_info['player_token']){ $key_counter++; continue; }
        //elseif ($key_counter > 0){ continue; }

        // If this is THE specific player requested (and one was specified)
        if (!empty($this_current_token) && $this_current_token == $player_info['player_token']){
            $this_current_key = $player_key;

            $this_player_image = !empty($player_info['player_image']) ? $player_info['player_image'] : $player_info['player_token'];
            if ($this_player_image == 'player'){ $this_seo_robots = 'noindex'; }
            // Define the SEO variables for this page
            $this_seo_title_backup = $this_seo_title;
            $this_seo_title = $player_info['player_name'].' | '.$this_seo_title;
            $this_seo_description = $player_info['player_name'].', one of the playable characters in the Mega Man RPG Prototype. '.$this_seo_description;
            // Define the Open Graph variables for this page
            $this_graph_data['title'] .= ' | '.$player_info['player_name'];
            $this_graph_data['description'] = $player_info['player_name'].', one of the playable characters in the Mega Man RPG Prototype. '.$this_graph_data['description'];
            $this_graph_data['image'] = MMRPG_CONFIG_ROOTURL.'images/players/'.$player_info['player_token'].'/mug_right_80x80.png?'.MMRPG_CONFIG_CACHE_DATE;

        }

        // Collect the markup for this player and print it to the browser
        $temp_player_markup = rpg_player::print_database_markup($player_info, array('show_key' => $key_counter));
        echo $temp_player_markup;
        $key_counter++;
        break;

    }

}

// Only show the header if a specific player has not been selected
if (empty($this_current_token)){
    ?>
    <h2 class="subheader field_type_<?= isset($this_current_filter) ? $this_current_filter : MMRPG_SETTINGS_CURRENT_FIELDTYPE ?>">
        Player Index
        <?= isset($this_current_filter) ? '<span class="count" style="float: right;">( '.$this_current_filter_name.' Type )</span>' : '' ?>
    </h2>
    <?php
}

?>

<div class="subbody subbody_databaselinks <?= empty($this_current_token) ? 'subbody_databaselinks_noajax' : '' ?>" data-class="players" data-class-single="player" data-basetitle="<?= isset($this_seo_title_backup) ? $this_seo_title_backup : $this_seo_title ?>" data-current="<?= !empty($this_current_token) ? $this_current_token : '' ?>">

    <? if(empty($this_current_token)): ?>

        <div class="<?= !empty($this_current_token) ? 'toggle_body' : '' ?>" style="<?= !empty($this_current_token) ? 'display: none;' : '' ?>">
            <p class="text" style="clear: both;">
                The player database contains detailed information on <?= $mmrpg_database_players_links_counter == 1 ? 'the' : 'all' ?> <?= isset($this_current_filter) ? $mmrpg_database_players_links_counter.' <span class="type_span player_type player_type_'.$this_current_filter.'">'.$this_current_filter_name.' Type</span> ' : $mmrpg_database_players_links_counter.' ' ?><?= $mmrpg_database_players_links_counter == 1 ? 'playable character that appears ' : 'playable characters that appear ' ?> in the prototype, including <?= $mmrpg_database_players_links_counter == 1 ? 'its' : 'each player\'s' ?> unlockable abilities, battle quotes, sprite sheets, and more.
                Click <?= $mmrpg_database_players_links_counter == 1 ? 'the mugshot below to scroll to the' : 'any of the mugshots below to scroll to a' ?> player's summarized database entry and click the more link to see its full page with sprites and extended info. <?= isset($this_current_filter) ? 'If you wish to reset the player type filter, <a href="database/players/">please click here</a>.' : '' ?>
            </p>
            <div class="text iconwrap"><?= preg_replace('/data-token="([-_a-z0-9]+)"/', 'data-anchor="$1"', $mmrpg_database_players_links) ?></div>
        </div>
        <div style="clear: both;">&nbsp;</div>

    <? else: ?>

        <?
        // Collect the prev and next player tokens
        $prev_link = false;
        $next_link = false;
        if (!empty($this_current_key)){
            $key_index = array_keys($mmrpg_database_players);
            $min_key = 0;
            $max_key = count($key_index) - 1;
            $current_key_position = array_search($this_current_key, $key_index);
            $prev_key_position = $current_key_position - 1;
            $next_key_position = $current_key_position + 1;
            $find = array('href="', '<a ', '</a>', '<div ', '</div>');
            $replace = array('data-href="', '<span ', '</span>', '<span ', '</span>');
            // If prev key was in range, generate
            if ($prev_key_position >= $min_key){
                $prev_key = $key_index[$prev_key_position];
                $prev_info = $mmrpg_database_players[$prev_key];
                $prev_link = 'database/players/'.$prev_info['player_token'].'/';
                $prev_link_image = $mmrpg_database_players_links_index[$prev_key];
                $prev_link_image = str_replace($find, $replace, $prev_link_image);
            }
            // If next key was in range, generate
            if ($next_key_position <= $max_key){
                $next_key = $key_index[$next_key_position];
                $next_info = $mmrpg_database_players[$next_key];
                $next_link = 'database/players/'.$next_info['player_token'].'/';
                $next_link_image = $mmrpg_database_players_links_index[$next_key];
                $next_link_image = str_replace($find, $replace, $next_link_image);
            }

        }

        ?>

        <div class="link_nav">
            <? if (!empty($prev_link)): ?>
                <a class="link link_prev" href="<?= $prev_link ?>"><?= $prev_link_image ?></a>
            <? endif; ?>
            <? if (!empty($next_link)): ?>
                <a class="link link_next" href="<?= $next_link ?>"><?= $next_link_image ?></a>
            <? endif; ?>
            <a class="link link_return" href="database/players/">Return to Player Index</a>
        </div>

    <? endif; ?>
</div>

<?php

// Only show the header if a specific player has not been selected
if (empty($this_current_token)){
    ?>
    <h2 class="subheader field_type_<?= isset($this_current_filter) ? $this_current_filter : MMRPG_SETTINGS_CURRENT_FIELDTYPE ?>" style="margin-top: 10px;">
        Player Listing
        <?= isset($this_current_filter) ? '<span class="count" style="float: right;">( '.$this_current_filter_name.' Type )</span>' : '' ?>
    </h2>
    <?php
}

// If we're in the index view, loop through and display all players
if (empty($this_current_token)){
    // Loop through the player database and display the appropriate data
    $key_counter = 0;
    foreach($mmrpg_database_players AS $player_key => $player_info){
        // If a type filter has been applied to the player page
        if (isset($this_current_filter) && $this_current_filter == 'none' && $player_info['player_type'] != ''){ $key_counter++; continue; }
        elseif (isset($this_current_filter) && $this_current_filter != 'none' && $player_info['player_type'] != $this_current_filter){ $key_counter++; continue; }
        // Collect information about this player
        $this_player_image = !empty($player_info['player_image']) ? $player_info['player_image'] : $player_info['player_token'];
        if ($this_player_image == 'player'){ $this_seo_robots = 'noindex'; }
        // Collect the markup for this player and print it to the browser
        $temp_player_markup = rpg_player::print_database_markup($player_info, array('layout_style' => 'website_compact', 'show_key' => $key_counter));
        echo $temp_player_markup;
        $key_counter++;
    }
}

?>