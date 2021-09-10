jQuery(document).ready(function($){
      // Main
  var hiddenChordsClass = "chwp-chord-hidden";
  var toggleChordButton = $('button[name="chords"]');
  var toggleCommentsButton = $('button[name="podrobnytext"]');
  var allChordsLyricsWrappers = $(".chwp-chord-lyrics-wrapper");
  var allComments = $('.chwp-comment');

  $(allChordsLyricsWrappers).toggleClass(hiddenChordsClass);
  
  $(toggleChordButton).click(function () {
    $(allChordsLyricsWrappers).toggleClass(hiddenChordsClass);
    $(this).toggleClass('active');
    if ($(this).hasClass('active')) {
      $(transpose_up_button).removeAttr('disabled');
      $(transpose_down_button).removeAttr('disabled');
    } else { 
      $(transpose_down_button).attr('disabled', 'disabled');
      $(transpose_up_button).attr('disabled', 'disabled');
      }
  });
  $(toggleCommentsButton).click(function () {
    $(allComments).toggle();
    $(this).toggleClass('active');
  });

      /**
     * Transpose uppercase
     */
var match;
var chords =
    ['C','C#','D','D#','E','F','F#','G','G#','A','A#','H','C'];
var chordRegex = /C#|D#|F#|G#|A#|C|D|E|F|G|A|H/g;
var transpose_up_button = $('.single-song button[name="transposeup"]');
var transpose_down_button = $('.single-song button[name="transposedown"]');
transpose_up_button.click(function() {
    $('.chwp-chord').each(function() {
        var currentChord = $(this).text();
        var output = "";
        var parts = currentChord.split(chordRegex);
        var index = 0;
        while (match = chordRegex.exec(currentChord))
        {
            var chordIndex = chords.indexOf(match[0]);
            output += parts[index++] + chords[chordIndex+1];
        }
        output += parts[index];
        $(this).text(output);
    });
});

transpose_down_button.click(function() {
    $('.chwp-chord').each(function() {
        var currentChord = $(this).text();
        var output = "";
        var parts = currentChord.split(chordRegex);
        var index = 0;
        while (match = chordRegex.exec(currentChord))
        {
            var chordIndex = chords.indexOf(match[0],1);
            output += parts[index++] + chords[chordIndex-1];
        }
        output += parts[index];
        $(this).text(output);
    });
});
    /**
     * Transpose lowercase
     */
var matchl;
var chordsl =
    ['c','c#','d','d#','e','f','f#','g','g#','a','a#','h','c'];
var chordRegexl = /c#|d#|f#|g#|a#|c|d|e|f|g|a|h/g;

transpose_up_button.click(function() {
    $('.chwp-chord').each(function() {
        var currentChord = $(this).text();
        var output = "";
        var parts = currentChord.split(chordRegexl);
        var index = 0;
        while (matchl = chordRegexl.exec(currentChord))
        {
            var chordIndex = chordsl.indexOf(matchl[0]);
            output += parts[index++] + chordsl[chordIndex+1];
        }
        output += parts[index];
        $(this).text(output);
    });
});

transpose_down_button.click(function() {
    $('.chwp-chord').each(function() {
        var currentChord = $(this).text();
        var output = "";
        var parts = currentChord.split(chordRegexl);
        var index = 0;
        while (matchl = chordRegexl.exec(currentChord))
        {
            var chordIndex = chordsl.indexOf(matchl[0],1);
            output += parts[index++] + chordsl[chordIndex-1];
        }
        output += parts[index];
        $(this).text(output);
    });
});
// Button transpose functions
    var transpose_up_button = $('.single-song button[name="transposeup"]');
    var transpose_down_button = $('.single-song button[name="transposedown"]');
    transpose_up_button.attr('disabled','disabled');
    transpose_down_button.attr('disabled','disabled');
// Print
    $('button[name="print"]').on('click', function(){
        window.print();
    });
    });
