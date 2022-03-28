import { DateTime } from "luxon";

export default () => ({
    dateTime: false,
    date: "",
    year: "",
    month: "",
    day: "",
    error: false,
    componentsToDate() {
        if (this.year && this.month && this.day) {
            const dt = DateTime.fromObject({ month: this.month, day: this.day, year: this.year });
            if (dt.isValid) {
                this.date = dt.toSQLDate();
                this.dateTime = dt;
                this.error = false;
            } else {
                this.dateTime = false;
                this.date = "";
                this.error = true;
            }
        } else {
            this.dateTime = false;
            this.date = "";
        }
    },
    dateToComponents(date, error) {
        if (date) {
            let year, month, day;
            [year, month, day] = date.split("-");
            const dt = DateTime.fromObject({ month, day, year });
            if (dt.isValid) {
                this.date = date;
                this.dateTime = dt;
                this.year = year;
                this.month = month;
                this.day = day;
                this.error = false;
            } else {
                this.date = "";
                this.dateTime = false;
                this.error = true;
            }
        } else if (error) {
            this.error = true;
        }
    }
});
