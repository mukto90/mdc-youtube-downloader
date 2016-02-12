(function() {

    tinymce.PluginManager.add('my_mce_button_for_post', function( editor, url ) {

        editor.addButton( 'my_mce_button_for_post', {

            type: 'menubutton',

            image : wp_ulrs.yt_icon,

            title : 'MDC YouTube Downloader',

            menu: [

                {

                    text: 'Insert Downloadable YouTube Video',

                    value: 'Insert Downloadable YouTube Video',

                    onclick: function() {
                        alert('This feature is available in Pro version only!')
                    }

                },

                {

                    text: 'Create YouTube Downloader Form',

                    value: 'Create YouTube Downloader Form',

                    onclick: function() {

                        editor.windowManager.open( {

                            title: 'Create YouTube Downloader Form',

                            body: [

                                {

                                    type: 'textbox',

                                    name: 'placeholder',

                                    label: 'Form Placeholder',

                                    value: ''

                                },

                                {

                                    type: 'textbox',

                                    name: 'button_label',

                                    label: 'Label for Generate Button',

                                    value: ''

                                },

                                {

                                    type: 'listbox',

                                    name: 'show_thumb',

                                    label: 'Show Thumbnail',

                                    'values': [

                                        {text: 'Default', value: ''},

                                        {text: 'Yes', value: '1'},

                                        {text: 'No', value: '0'},

                                    ]

                                },

                                {

                                    type: 'textbox',

                                    name: 'thumb_height',

                                    label: 'Height of Thumbnail (If shown)',

                                    value: ''

                                },

                                {

                                    type: 'textbox',

                                    name: 'thumb_width',

                                    label: 'Width of Thumbnail (If shown)',

                                    value: ''

                                },

                                {

                                    type: 'listbox',

                                    name: 'show_quality',

                                    label: 'Show Video Quality',

                                    'values': [

                                        {text: 'Default', value: ''},

                                        {text: 'Yes', value: '1'},

                                        {text: 'No', value: '0'},

                                    ]

                                },

                                {

                                    type: 'textbox',

                                    name: 'label',

                                    label: 'Text Label of Download Button',

                                    value: ''

                                }

                            ],

                            onsubmit: function(e) {

                                var shortCode = '[youtube_downloader_form';

                                if(e.data.placeholder != ''){  shortCode +=  ' placeholder="' + e.data.placeholder +'"';}

                                if(e.data.button_label != ''){  shortCode +=  ' button_label="' + e.data.button_label +'"';}

                                if(e.data.show_thumb != ''){  shortCode +=  ' show_thumb="' + e.data.show_thumb +'"';}

                                if(e.data.thumb_height != ''){  shortCode +=  ' thumb_height="' + e.data.thumb_height +'"';}

                                if(e.data.thumb_width != ''){  shortCode +=  ' thumb_width="' + e.data.thumb_width +'"';}

                                if(e.data.show_quality != ''){  shortCode +=  ' show_quality="' + e.data.show_quality +'"';}

                                if(e.data.label != ''){  shortCode +=  ' label="' + e.data.label +'"';}

                                shortCode += ']';

                                editor.insertContent(shortCode);

                                // editor.insertContent( '[youtube_downloader_form placeholder="'+ e.data.placeholder +'" button_label="'+ e.data.button_label +'" show_thumb="'+ e.data.show_thumb +'" thumb_height="'+ e.data.thumb_height +'" thumb_width="'+ e.data.thumb_width +'" show_quality="'+ e.data.show_quality +'" label="'+ e.data.label +'"]');

                            }

                        });

                    }

                }

           ]

        });

    });

})();