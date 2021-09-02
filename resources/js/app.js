require("./bootstrap");

import Alpine from "alpinejs";

import confirmPassword from "./confirmPassword.js";
import dateInput from "./dateInput.js";
import tabs from "./tabs.js";

Alpine.data("confirmPassword", confirmPassword);
Alpine.data("dateInput", dateInput);
Alpine.data("tabs", tabs);

Alpine.start();
