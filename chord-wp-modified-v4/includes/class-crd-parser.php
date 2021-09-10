<?php

class CRD_Parser {

    public $using_columns = false;
    public $columns_started = false;
    
    private $last_row_had_only_chords = false;
    private $last_row_had_comment = false;

    /**
     * Parse the song content into viewable format
     *
     * @param  string $content The song content
     * @return string The content parsed for viewing in HTML format
     */
    public function run ( $content, $show_chords=true ) {
        $lines = explode ( "\n", $content );
        $out = '';

        foreach ( $lines as $line ) {
            $out .= $this->parse_line ( $line, $show_chords );
        }

        $out .= $this->close_formatting();
        
        return $out;
    }
    
    /**
     * CUSTOM NEW FORMAT (TEST VERSION)
     * Parse the song content into viewable format
     *
     * @param  string $content The song content
     * @return string The content parsed for viewing in HTML format
     */
    public function run_new ( $content, $show_chords=true ) {
        $content = $this->convert_tags_to_nl($content);
        $lines = explode ( "\n", $content );
        $out = '';
        
        // $out .= '<button id="toggle-chords">Toggle chords</button>';
        // $out .= '<button id="toggle-song-comments">Toggle comments</button><br>';
        
        foreach ( $lines as $line ) {
            $out .= $this->parse_line_new ( $line, $show_chords );
        }

        $out .= $this->close_formatting();
        
        return $out;
    }
    
    public function parse_line ( $raw_line, $show_chords=true ) {
        $html = '';
        $chords = array();
        $lyrics = array();
        $line = trim ( strip_tags ( $raw_line ) );

        if ( ! empty ( $line ) ) {
            if ( Chordwp::starts_with ( $line, '{' ) ) {
                $html = $this->parse_directive ( $line );
            }
            else {
                // Start table for columns on first line that is not a directive
                $html = '';
                if ( $this->using_columns === true && $this->columns_started === false ) {
                    $html = $this->start_columns();
                }

                if ( strpos ( $line, '[' ) !== false ) {
                    $html .= $this->build_table ( $line, $show_chords );
                }
                else {
                    $html .= $this->render_lyrics( $line );
                }
            }
        }

        return $html;
    }

    public function parse_line_new ( $raw_line, $show_chords=true, $additionalClasses = '' ) {
        $html = '';
        $chords = array();
        $lyrics = array();
        $line = trim ( strip_tags ( $raw_line ) );

        if ( ! empty ( $line ) ) {
            if ( Chordwp::starts_with ( $line, '{' ) ) {
                $html = $this->parse_directive ( $line );
            }
            else {
                // Start table for columns on first line that is not a directive
                $html = '';
                if ( $this->using_columns === true && $this->columns_started === false ) {
                    $html = $this->start_columns();
                }

                $html .= $this->build_lyrics_and_chords ( $line, $show_chords, $additionalClasses );
            }
        } else {
            if ($this->last_row_had_only_chords) {
                $html .= '<div class="chwp-lyrics-row chwp-chords-in-line chwp-no-lyrics-in-line">';
                $html .= '<span class="chwp-chord-lyrics-wrapper">';
                $html .= '<span class="chwp-chord-wrapper">';
                $html .= '<span class="chwp-chord chwp-empty-chord">&nbsp;</span>';
                $html .= '</span>'; // end chwp-chord-wrapper
                $html .= '<span class="chwp-lyrics"></span>';
                $html .= '</span>'; // end chwp-chord-lyrics-wrapper
                $html .= '</div>'; // end chwp-lyrics-row
                
                $this->last_row_had_only_chords = false;
            }
            else if ($this->last_row_had_comment) {
                $html .= '<span class="chwp-comment" style="display: none;"><br /></span>';
                
                $this->last_row_had_comment = false;
            }
            else {
                $html .= '<br />';
            }
        }

        return $html;
    }

    public function parse_directive ( $line ) {
        $out = '';
        $line = trim ( trim ( $line, '{}' ) );

        if ( Chordwp::contains ( $line, ':' ) ) {
            list ( $directive, $content ) = explode ( ':', $line, 2 );
        }
        else {
            $directive = $line;
        }

        if ( ! empty ( $directive ) ) {
            $directive = strtolower ( $directive );

            switch ( $directive ) {
                case 't':
                case 'title':
                    $out = $this->render_title ( $content );
                    break;
                case 'artist':
                    $out = $this->render_directive( 'Artist', $content );
                    break;
                case 'key':
                    $out = $this->render_directive( 'Key', $content );
                    break;
                case 'capo':
                    $out = $this->render_directive( 'Capo', $content );
                    break;
                case 'time':
                    $out = $this->render_directive( 'Time Signature', $content );
                    break;
                case 'tempo':
                    $out = $this->render_directive( 'Tempo', $content );
                    break;
                case 'soc':
                case 'start_of_chorus':
                    $out = $this->render_start_of_chorus();
                    break;
                case 'eoc':
                case 'end_of_chorus':
                    $out = $this->render_end_of_chorus();
                    break;
                case 'c':
                case 'comment':
                    $out = $this->render_comment( $content );
                    break;
                case 'ci':
                case 'comment_italic':
                    $out = $this->render_comment_italic( $content );
                    break;
                case 'cb':
                case 'comment_box':
                    $out = $this->render_comment_box( $content );
                    break;
                case 'columns':
                case 'col':
                    $this->using_columns = true;
                    break;
                case 'colb':
                case 'column_break':
                    $out = '<div class="chwp-line"></div>';
                    break;
                default:
                    if ( ! empty( $content ) ) {
                        $out = $this->render_comment( $content );
                    }
            }
        }

        return $out;
    }

    public function render_directive ( $label, $content ) {
        if ( 'Key' == $label ) {
            if ( has_filter( 'crd_the_chord' ) ) {
                $content = trim( $content );
                $content = apply_filters( 'crd_the_chord', $content );
            }
        }

        $html = '<p class="directive">' . $label . ': ' . $content . '</p>';
        return $html;
    }

    public function render_lyrics ( $content ) {
        $html = '<p class="lyrics">' . $content . '</p>';
        return $html;
    }

    public function render_comment ( $content ) {
        $html = '<span class="chwp-comment" style="display: none;">' . $content . '<br></span>';
        $this->last_row_had_comment = true;
        $this->last_row_had_only_chords = false;
        
        return $html;
    }

    public function render_comment_italic ( $content ) {
        $html = $this->build_lyrics_and_chords ( $content, true, 'font-italic' );;
        return $html;
    }

    public function render_comment_box ( $content ) {
        $html = '<p class="comment-box">' . $content . '</p>';
        return $html;
    }

    public function render_title( $content ) {
        $html = '<h1 class="song-title">' . $content . '</h1>';
        return $html;
    }

    public function render_start_of_chorus() {
        $html = '<div class="chorus">' . "\n";
        return $html;
    }

    public function render_end_of_chorus() {
        $html = '</div>' . "\n";
        return $html;
    }


    public function start_columns() {
        $this->columns_started = true;
        $html = '<div class="chordwp-column">';
        return $html;
    }

    public function column_break() {
        $html = '</div><div class="chordwp-column">';
        return $html;
    }

    public function end_columns() {
        $html = '</div><div class="chordwp-clearfix"></div>';
        return $html;
    }

    public function close_formatting() {
        $out = '';
        if ( $this->using_columns ) {
            $out .= $this->end_columns ();
        }

        return $out;
    }
    
    public function build_table( $line, $show_chords=true ) {
        $parts = explode('[', $line);

        foreach ( $parts as $part ) {
            if ( ! empty( $part ) ) {
                $phrase = explode(']', $part);
                if ( count( $phrase ) == 2 ) {
                    $chords[] = $phrase[0];
                    $lyrics[] = empty ( $phrase[1] ) ? '&nbsp;' : $phrase[1];
                }
                elseif ( count( $phrase ) == 1 ) {
                    $chords[] = '&nbsp;';
                    $lyrics[] = $phrase[0];
                }
            }
        }

        $table = '<table class="verse-line">' . "\n";

        // Render chords
        if ( $show_chords ) {
           $table .= '  <tr>' . "\n";
            foreach ( $chords as $chord ) {

                if ( has_filter( 'crd_the_chord' ) ) {
                    $chord = apply_filters( 'crd_the_chord', $chord );
                }

                $table .= '    <td class="chords">' . $chord . '</td>' .  "\n";
            }
            $table .= '  </tr>' . "\n"; 
        }
        
        // Render lyrics
        $table .= '  <tr>' . "\n";

        if ( $show_chords ) {
           foreach ( $lyrics as $lyric ) {
                $lyric = trim( $lyric );
                $lyric = strtr( $lyric, array(' ' => '&nbsp;') );
                $table .= '    <td class="lyrics">' . $lyric . '</td>' .  "\n";
            } 
        }
        else {
            $lyrics = implode( ' ', $lyrics );
            $lyrics = preg_replace( '/\s+-\s+/', '', $lyrics );
            $table .= '    <td class="lyrics">' . $lyrics . '</td>' .  "\n";
        }
        
        $table .= '  </tr>' . "\n";


        $table .= '</table>';

        return $table;
    }
    
    public function isWordDivided($parts, $countOfParts, $currentIndex) {
        $currentPart = explode(']', $parts[$currentIndex]);
        
        if (substr($currentPart[1], 0, 1) === " ") {
            return false;
        }
        else {
            if (empty($currentPart[1])) {
                if ($currentIndex + 1 === $countOfParts) {
                    return false;
                }
                
                return $this->isWordDivided($parts, $countOfParts, $currentIndex + 1);
            }
            else {
                return true;
            }
        }
    }

    public function build_lyrics_and_chords( $line, $show_chords=true, $additionalClasses='' ) {
        $chordLyricsWrappers = array();
        $lyricsInWrappers = array();
        $chordLyricsWrappersIndex = 0;
        
        $chordWrapperOpen = false;
        $chordInLine = false;
        $lyricsInLine = false;
        
        $parts = explode('[', $line);
        // print_r('<pre>');
        // var_dump($parts);
        // print_r('</pre>');
        $countOfParts = count($parts);
        $partIndex = 1;

        foreach ( $parts as $part ) {
            $isLastPart = $partIndex === $countOfParts;
            // $nextPhrase = [];
            // if (!$isLastPart) {
            //     $nextPhrase = explode(']', $parts[$partIndex]);
            // }
            // $isNextPartChord = $this->isNextPartChord($parts, $partIndex);
            // $isNextPartChord = count($nextPhrase) === 2 
            //     && (empty(trim($nextPhrase[1])) || substr($nextPhrase[1], 0, 1) === " ");
            
            if ( ! empty( $part ) ) {
                $phrase = explode(']', $part);
                
                if (!$chordWrapperOpen) {
                    $chordLyricsWrappers[$chordLyricsWrappersIndex] .= '<span class="chwp-chord-lyrics-wrapper">';
                    $chordLyricsWrappers[$chordLyricsWrappersIndex] .= '<span class="chwp-chord-wrapper">';
                    
                    $chordWrapperOpen = true;
                }
                
                $showLyrics = false;
                $showChords = false;
                $wordSeparated = false;
                $standaloneChord = false;
                $chord = '';
                $lyrics = '';
                
                if ( count( $phrase ) == 2 ) {
                    $showChords = true;
                    $chordInLine = true;
                    $chord = $phrase[0];
                    $lyrics = $phrase[1];

                    if (substr($lyrics, -1) !== " " && !$isLastPart && $this->isWordDivided($parts, $countOfParts, $partIndex)) {
                        $wordSeparated = true;
                    }
                    
                    if (substr($lyrics, 0, 1) === " ") {
                        $standaloneChord = true;
                    }
                }
                elseif ( count( $phrase ) == 1 ) {
                    $lyrics = $phrase[0];
                }
                
                // echo $lyrics . ', NPCH: '.$isNextPartChord . ', ISLAST: ' . $isLastPart;
                
                $showLyrics = !empty(trim($lyrics));
                
                $emptyChordClass = empty(trim($chord)) ? ' chwp-empty-chord' : '';
                $chordLyricsWrappers[$chordLyricsWrappersIndex] .= '<span class="chwp-chord'. $emptyChordClass .'">'. $chord .'&nbsp;</span>';
                
                if ($standaloneChord) {
                    $chordLyricsWrappers[$chordLyricsWrappersIndex] .= '</span>'; // end chord-wrapper
                    $chordLyricsWrappers[$chordLyricsWrappersIndex] .= '<span class="chwp-lyrics">';
                    $chordLyricsWrappers[$chordLyricsWrappersIndex] .= '</span>'; // end lyrics
                    $chordLyricsWrappers[$chordLyricsWrappersIndex] .= '</span>'; // end chord-lyrics-wrapper
                    
                    $lyricsInWrappers[$chordLyricsWrappersIndex] = false;
                    $chordLyricsWrappersIndex++;
                    
                    $chordLyricsWrappers[$chordLyricsWrappersIndex] .= '<span class="chwp-chord-lyrics-wrapper">';
                    $chordLyricsWrappers[$chordLyricsWrappersIndex] .= '<span class="chwp-chord-wrapper">';
                    $chordLyricsWrappers[$chordLyricsWrappersIndex] .= '<span class="chwp-chord chwp-empty-chord">&nbsp;</span>';
                    
                    $lyrics = substr($lyrics, 1, strlen($lyrics));
                }
                
                $lyrics = str_replace(" ", "&nbsp;", $lyrics);
                
                if ($showLyrics) {
                    $chordLyricsWrappers[$chordLyricsWrappersIndex] .= '</span>'; // end chwp-chord-wrapper
                
                    $chordLyricsWrappers[$chordLyricsWrappersIndex] .= '<span class="chwp-lyrics">';
                    $chordLyricsWrappers[$chordLyricsWrappersIndex] .= '<span class="chwp-words">'. $lyrics .'</span>';
                    
                    if ($wordSeparated) {
                        $chordLyricsWrappers[$chordLyricsWrappersIndex] .= '<span class="chwp-word-separator"><span class="chwp-flexible-dash"></span></span>';
                    }
                    $chordLyricsWrappers[$chordLyricsWrappersIndex] .= '</span>'; // end chwp-lyrics
                    $chordLyricsWrappers[$chordLyricsWrappersIndex] .= '</span>'; // end chwp-chord-lyrics-wrapper
                    
                    $lyricsInLine = true;
                    $chordWrapperOpen = false;
                    $lyricsInWrappers[$chordLyricsWrappersIndex] = true;
                    
                    $chordLyricsWrappersIndex++;
                }
                else {
                    $lyricsInWrappers[$chordLyricsWrappersIndex] = false;
                }
            }
            
            $partIndex++;
        }
        
        if ($chordWrapperOpen) {
            $chordLyricsWrappers[$chordLyricsWrappersIndex] .= '</span>'; // end chwp-chord-wrapper
            $chordLyricsWrappers[$chordLyricsWrappersIndex] .= '<span class="chwp-lyrics"></span>';
            $chordLyricsWrappers[$chordLyricsWrappersIndex] .= '</span>'; // end chwp-chord-lyrics-wrapper
        }
        
        $lyricsWrappersCount = count($chordLyricsWrappers);
        $showInLeftPartUpToIndex = -1;
        $showInRightPartFromIndex = $lyricsWrappersCount;
        
        if ($lyricsInLine) {
            for($i = 0; $i < $lyricsWrappersCount; $i++) {
                if (!$lyricsInWrappers[$i]) {
                    $showInLeftPartUpToIndex = $i;
                }
                else {
                    break;
                }
            }
            
            for ($i = $lyricsWrappersCount - 1; $i > $showInLeftPartUpToIndex; $i--) {
                if (!$lyricsInWrappers[$i]) {
                    $showInRightPartFromIndex = $i;
                }
                else {
                    break;
                }
            }
        }
        
        $showSideRowParts = $showInLeftPartUpToIndex > -1 || $showInRightPartFromIndex < $lyricsWrappersCount;
        
        $chordsInLineClass = $chordInLine ? ' chwp-chords-in-line' : ' chwp-no-chords-in-line';
        $lyricsInLineClass = $lyricsInLine ? ' chwp-lyrics-in-line' : ' chwp-no-lyrics-in-line';
        
        $finalHtml = '<div class="chwp-lyrics-row-wrapper '. $additionalClasses .'">';
        
        if ($showSideRowParts) {
            $finalHtml .= '<div class="chwp-lyrics-row-side chwp-side-left chwp-no-lyrics-inside">';
            for ($i = 0; $i <= $showInLeftPartUpToIndex; $i++) {
                $finalHtml .= $chordLyricsWrappers[$i];
            }
            $finalHtml .= '</div>'; // end of chwp-lyric-row-side
        }

        $finalHtml .= '<div class="chwp-lyrics-row' . $chordsInLineClass . $lyricsInLineClass . '">';
        for ($i = $showInLeftPartUpToIndex + 1; $i < $showInRightPartFromIndex; $i++) {
            $finalHtml .= $chordLyricsWrappers[$i];
        }
        $finalHtml .= '</div>'; // end of chwp-lyrics-row
        
        if ($showSideRowParts) {
            $finalHtml .= '<div class="chwp-lyrics-row-side chwp-side-right chwp-no-lyrics-inside">';
            for ($i = $showInRightPartFromIndex; $i < $lyricsWrappersCount; $i++) {
                $finalHtml .= $chordLyricsWrappers[$i];
            }
            $finalHtml .= '</div>'; // end of chwp-lyrics-row-side
        }
        
        $finalHtml .= '</div>'; // end of chwp-lyrics-row-wrapper
        
        $finalHtml = $this->convert_directives_to_html($finalHtml);
        
        $this->last_row_had_only_chords = !$lyricsInLine;
        $this->last_row_had_comment = false;

        return $finalHtml;
    }
    
    private function convert_directives_to_html($content) {
        $matches = array();

        preg_match_all('/\{ci:[^\}]*\}/', $content, $matches);
        $finalReplacements = array();
        
        foreach ($matches as $group) {
            foreach ($group as $occurrence) {
                // remove {ci: from start
                $substr = substr($occurrence, 4);
                // remove } from end
                $substr = substr($substr, 0, strlen($substr) - 1);
                
                $substr = preg_replace('/<[^>]*>/', '|', $substr);
                $allParts = explode('|', $substr);
                $partsToReplaceInOccurrence = array();
                
                // prepare parts for replacement
                foreach ($allParts as $part) {
                    if (!empty(trim($part))) {
                        $partsToReplaceInOccurrence[$part] = '<i>'.$part.'</i>';
                    }
                }
                
                $finalReplacement = $occurrence;
                foreach ($partsToReplaceInOccurrence as $search => $replace) {
                    $s = $search;
                    $r = $replace;
                    $finalReplacement = str_replace($s, $r, $finalReplacement);
                }
                
                // remove {ci: from start
                $finalReplacement = substr($finalReplacement, 4);
                // remove } from end
                $finalReplacement = substr($finalReplacement, 0, strlen($finalReplacement) - 1);
                $finalReplacement = ltrim($finalReplacement);
                if ($this->starts_with($finalReplacement, '<i>&nbsp;')) {
                    $finalReplacement = '<i>'.substr($finalReplacement, 9);
                }
                $finalReplacements[] = [$occurrence, $finalReplacement];
            }
        }
        
        foreach ($finalReplacements as $replacement) {
            $search = $replacement[0];
            $replace = $replacement[1];
            $content = str_replace($search, $replace, $content);
        }

        return $content;
    }
    
    private function starts_with($haystack, $needle)
    {
        $length = strlen($needle);
        return (substr($haystack, 0, $length) === $needle);
    }
    
    private function convert_tags_to_nl($htmlContent) {
        // Make sure that "<br />\n" is understood as one new-line
        $contentWithNLs = preg_replace('#\\n\s*<br\s*/?>#i', "\n", $htmlContent);
        // Add new line when paragaph ends
        $contentWithNLs = str_replace("</p>", "\n", $contentWithNLs);
        
        // Remove all other html tags
        return strip_tags($contentWithNLs);
    }
}
