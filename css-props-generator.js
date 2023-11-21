import { writeFileSync } from "node:fs";
import * as prettier from "prettier";
import {default as config} from "./tailwind.config.js";

/*
  Converts the tailwind config elements into custom props.
*/
export const generateCSSProps = async () => {
    let result = "";

    const groups = [
        {key: "spacing", prefix: "space"},
        {key: "borderWidth", prefix: "border"},
        {key: "borderRadius", prefix: "radius"},
        {key: "fontSize", prefix: "text"},
        {key: "fontFamily", prefix: "font"},
        {key: "fontWeight", prefix: "font"},
        {key: "maxWidth", prefix: "max-w"}
    ];

    const extendedGroups = [
        {key: "colors", prefix: "color"},
    ];

    // Add a note that this is auto generated
    result += `
    /* VARIABLES GENERATED WITH TAILWIND CONFIG ON ${new Date().toLocaleDateString()}.
    Tokens location: ./tailwind.config.js */
    :root {
  `;

    // Loop each group's keys, use that and the associated
    // property to define a :root custom prop
    groups.forEach(({key, prefix}) => {
        const group = config.theme[key];

        if (!group) {
            return;
        }

        Object.keys(group).forEach(key => {
            if (key === "DEFAULT") {
                result += `--${prefix}: ${Array.isArray(group[key]) ? group[key][0] : group[key]};`;
            } else {
                result += `--${prefix}-${key.replace(".", "_")}: ${Array.isArray(group[key]) ? group[key][0] : group[key]};`;
            }
        });
    });

    extendedGroups.forEach(({key, prefix}) => {
        const group = config.theme.extend[key];

        if (!group) {
            return;
        }

        Object.keys(group).forEach(key => {
            if (key === "DEFAULT") {
                result += `--${prefix}: ${Array.isArray(group[key]) ? group[key][0] : group[key]};`;
            } else {
                result += `--${prefix}-${key.replace(".", "_")}: ${Array.isArray(group[key]) ? group[key][0] : group[key]};`;
            }
        });
    });

    // Close the :root block
    result += `
    }
  `;

    // Make the CSS readable to help people with auto-complete in their editors
    result = await prettier.format(result, {parser: "scss", tabWidth: 4});

    // Push this file into the CSS dir, ready to go
    writeFileSync("./resources/css/_tokens.css", result);
};

generateCSSProps();
