(function() {
    tinymce.PluginManager.add('gk_tc_button2', function( editor, url ) {
        editor.addButton( 'gk_tc_button2', {
            title: 'Podrobný text',
            type: 'menubutton',
            icon: false,
            menu: [
                {
                    text: 'Slohy',
                    value: 'Slohy',
                    onclick: function() {
                        editor.insertContent('');
                    },
                    menu: [
                        {
                            text: '1',
                            value: '1',
                            onclick: function(e) {
                                e.stopPropagation();
                                editor.insertContent('{c: 1}');
                            }      
                        },
                        {
                            text: '2',
                            value: '2',
                            onclick: function(e) {
                                e.stopPropagation();
                                editor.insertContent('{c: 2}');
                            }      
                        },
                        {
                            text: '3',
                            value: '3',
                            onclick: function(e) {
                                e.stopPropagation();
                                editor.insertContent('{c: 3}');
                            }      
                        },
                        {
                            text: '4',
                            value: '4',
                            onclick: function(e) {
                                e.stopPropagation();
                                editor.insertContent('{c: 4}');
                            }      
                        }
                    ]
                },
                {
                    text: 'Časti',
                    value: 'Časti',
                    onclick: function() {
                        editor.insertContent('');
                    },
                    menu: [
                        {
                            text: 'Intro',
                            value: 'Intro',
                            onclick: function(e) {
                                e.stopPropagation();
                                editor.insertContent('{c: Intro}');
                            }      
                        },
                        {
                            text: 'Prechorus',
                            value: 'Prechorus',
                            onclick: function(e) {
                                e.stopPropagation();
                                editor.insertContent('{c: Prechorus}');
                            }      
                        },
                        {
                            text: 'Chorus',
                            value: 'Chorus',
                            onclick: function(e) {
                                e.stopPropagation();
                                editor.insertContent('{c: Chorus}');
                            }      
                        },
                        {
                            text: 'Outro',
                            value: 'Outro',
                            onclick: function(e) {
                                e.stopPropagation();
                                editor.insertContent('{c: Outro}');
                            }      
                        }
                    ]
                },
                {
                    text: 'Doplnky',
                    value: 'Doplnky',
                    onclick: function() {
                        editor.insertContent('');
                    },
                    menu: [
                        {
                            text: 'Bridge',
                            value: 'Bridge',
                            onclick: function(e) {
                                e.stopPropagation();
                                editor.insertContent('{c: Bridge}');
                            }      
                        },
                        {
                            text: 'Interlude',
                            value: 'Interlude',
                            onclick: function(e) {
                                e.stopPropagation();
                                editor.insertContent('{c: Interlude}');
                            }      
                        },
                        {
                            text: 'Woah',
                            value: 'Woah',
                            onclick: function(e) {
                                e.stopPropagation();
                                editor.insertContent('{c: Woah}');
                            }      
                        },
                        {
                            text: 'Variabilné',
                            value: 'Variabilné',
                            onclick: function(e) {
                                e.stopPropagation();
                                editor.insertContent('{c: }');
                            }      
                        },
						{
                            text: 'Italic',
                            value: 'Italic',
                            onclick: function(e) {
                                e.stopPropagation();
                                editor.insertContent('{ci: }');
                            }      
                        }
                    ]
                }
           ]
        });
    });
})();