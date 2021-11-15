require("./bootstrap");

import Alpine from "alpinejs";

import confirmPassword from "./confirmPassword.js";
import dateInput from "./dateInput.js";

Alpine.data("confirmPassword", confirmPassword);
Alpine.data("dateInput", dateInput);

Alpine.start();
