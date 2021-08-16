<?php
$customizer = array( // Panels
  "seg-customizer-options" => array(
    "ptitle" => "SEG Options",
    "pdomain" => "seg-theme",
    "pcapability" => "edit_theme_options",
    "psections" => array( // Sections
      "seg-theme-tracking" => array(
        "stitle" => "Tracking Sources",
        "sdomain" => "seg-theme",
        "spriority" => 115,
        "scapability" => "edit_theme_options",
        "sdescription" => "Specific tracking resources you can choose to implement.",
        "sfields" => array( // Fields
          "seg_radio_facebook" => array(
            "fdefault" => "no",
            "fcapability" => "edit_theme_options",
            "flabel" => "Facebook Pixel",
            "fdomain" => "seg-theme",
            "fstype" => "option",
            "ftype" => "radio",
            "fsettings" => "seg_radio_facebook",
            "fchoices" => array(
              "yes" => "Yes",
              "no" => "No"
            )
          ),
          "seg_textarea_fbpixelcode" => array(
            "fdefault" => "",
            "fcapability" => "edit_theme_options",
            "flabel" => "Facebook Pixel Code",
            "fdomain" => "seg-theme",
            "fstype" => "option",
            "ftype" => "textarea",
            "fsettings" => "seg_tracking_fbpixelcode",
            "fattributes" => array(
              "placeholder" => "Use %s to mark where additional tags should go. ",
              "rows" => "10",
              "wrap" => "off"
            )
          )
        ) // End Fields
      ),
      "seg-theme-resources" => array(
        "stitle" => "Additional Resources",
        "sdomain" => "seg-theme",
        "spriority" => 115,
        "scapability" => "edit_theme_options",
        "sdescription" => "Additional resources you can opt to include.",
        "sfields" => array( // Fields
          "seg_checkbox_bootstrap" => array(
            "fdefault" => "no",
            "fcapability" => "edit_theme_options",
            "flabel" => "Include Bootstrap",
            "fdomain" => "seg-theme",
            "fstype" => "option",
            "ftype" => "radio",
            "fsettings" => "seg_include_bootstrap",
            "fchoices" => array(
              "yes" => "Yes",
              "no" => "No"
            )
          ),
          "seg_checkbox_fontawesome" => array(
            "fdefault" => "no",
            "fcapability" => "edit_theme_options",
            "flabel" => "Include FontAwesome",
            "fdomain" => "seg-theme",
            "fstype" => "option",
            "ftype" => "radio",
            "fsettings" => "seg_include_fontawesome",
            "fchoices" => array(
              "yes" => "Yes",
              "no" => "No"
            )
          )
        ) // End Fields
      )
    )
  )
);
?>
