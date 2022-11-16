export default (initial = "") => ({
    date: "",
    year: false,
    month: false,
    day: false,
    valid: null,
    validationError: "",
    init() {
        this.date = initial;
        let [y, m, d] = this.date.split("-");
        this.year = y ?? "";
        this.month = m ?? "";
        this.day = d ?? "";
    },
    getDate() {
        if (this.year || (this.year !== "" && this.month) || (this.month !== "" && this.day) || this.day !== "") {
            if (this.isValidDate(this.year, this.month, this.day)) {
                return new Date(this.year, this.month - 1, this.day).toISOString().split("T")[0];
            } else {
                return [this.year, this.month, this.day].join("-");
            }
            return "";
        }
    },
    /**
     * Get the number of days in any particular month
     * @see https://stackoverflow.com/a/1433119/1293256
     * @param  {integer} m - The month (valid: 0-11)
     * @param  {integer} y - The year
     * @return {integer} The number of days in the month
     */
    daysInMonth(m, y) {
        switch (m) {
            case 1:
                return (y % 4 === 0 && y % 100) || y % 400 === 0 ? 29 : 28;
            case 8:
            case 3:
            case 5:
            case 10:
                return 30;
            default:
                return 31;
        }
    },
    /**
     * Check if a date is valid
     * @see https://stackoverflow.com/a/1433119/1293256
     * @param  {integer} y - The year
     * @param  {integer} m - The month
     * @param  {integer} d - The day
     * @return {Boolean} Returns true if valid
     */
    isValidDate(y, m, d) {
        m = parseInt(m, 10) - 1;
        return m >= 0 && m < 12 && d > 0 && d <= this.daysInMonth(m, y);
    }
});
