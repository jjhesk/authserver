/**
 * Created by ryo on 14年9月10日.
 */

var CheckPointChartAdmin = {};
jQuery(function ($) {
    CheckPointChartAdmin = function (component_name, action_id, occurrence, cycle) {
        this.$container = $("#" + component_name);
        this.action_id = action_id;
        this.occurrence = occurrence;
        this.cycle = cycle;
        this.ctx = $("#checkpoint_chart").get(0).getContext("2d");
        this.missionBarChart;
        console.log(this.occurrence);
        this.ChartInit();
    };
    CheckPointChartAdmin.prototype = {
        ChartInit: function () {
            var d = this;

            var data = {
                labels: ["January", "February", "March", "April", "May", "June",
                    "July", "August", "September", "October", "November", "December"],
                datasets: [
                    {
                        label: "Check Point Mission Data",
                        fillColor: "rgba(151,187,205,0.5)",
                        strokeColor: "rgba(151,187,205,0.8)",
                        highlightFill: "rgba(151,187,205,0.75)",
                        highlightStroke: "rgba(151,187,205,1)",
                        data: [0, 0, 0, 0, 0, 0, 0]
                    }
                ]
            };

            var options = {
                responsive: true,
                maintainAspectRatio: false
            };

            d.missionBarChart = new Chart(d.ctx).Bar(data, options);

            d.missionBarChart.datasets[0].bars[2].value = 50;

            d.missionBarChart.update();
        }
    }
});