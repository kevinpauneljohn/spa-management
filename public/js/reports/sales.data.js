/* global Chart:false */

// $(function () {
    //'use strict'

    var ticksStyle = {
      fontColor: '#495057',
      fontStyle: 'bold'
    }

    var mode = 'index'
    var intersect = true

    var $salesChart = $('#sales-chart')
    // eslint-disable-next-line no-unused-vars
    var salesChart = new Chart($salesChart, {
      type: 'bar',
      data: {
          labels: [],
          datasets: [{
              label: 'Current Year',
              data: [],
              backgroundColor: '#17a2b8',
              borderColor: '#17a2b8',
              borderWidth: 1
          }, {
              label: 'Last Year',
              data: [],
              backgroundColor: '#6c757d',
              borderColor: '#6c757d',
              borderWidth: 1
          }]
      },
      options: {
        maintainAspectRatio: false,
        tooltips: {
          mode: mode,
          intersect: intersect
        },
        hover: {
          mode: mode,
          intersect: intersect
        },
        legend: {
          display: false
        },
        scales: {
          yAxes: [{
            // display: false,
            gridLines: {
              display: true,
              lineWidth: '4px',
              color: 'rgba(0, 0, 0, .2)',
              zeroLineColor: 'transparent'
            },
            ticks: $.extend({
              beginAtZero: true,

              callback: function (value) {
                if (value >= 1000) {
                  value /= 1000
                  value += 'k'
                }

                return '₱' + value;
              }
            }, ticksStyle)
          }],
          xAxes: [{
            display: true,
            gridLines: {
              display: false
            },
            ticks: ticksStyle
          }]
        }
      }
    });

    function loadData() {
      var owner_id = $('.salesOwnerId').val();
      var spa_id = $('.salesSpaId').val();
      var type;
      if (spa_id.length > 0) {
        type = 'not_all';
      } else {
        type = 'all';
      }

    let overlay = '<div class="overlay"><i class="fas fa-2x fa-sync fa-spin"></i></div>';

      $.ajax({
          url: '/get-sales-report/'+owner_id,
          type: 'GET',
          data: {
            type: type,
            spa_id: spa_id
          },
          dataType: 'json',
          beforeSend: function (xhr) {
              $('.sales-chart').prepend(overlay)
          },
          success: function(data) {
              // console.log(data)
              if (data.sales) {
                salesChart.data.labels = data.sales.labels;
                salesChart.data.datasets[0].data = data.sales.currentYearValues;
                salesChart.data.datasets[1].data = data.sales.lastYearValues;
                salesChart.update();

                if (data.sales.percentageSalesStatus) {
                  if (!$('.textPercentage').hasClass('text-success')) {
                    $('.textPercentage').addClass('text-success');
                    $('.textPercentage').removeClass('text-danger');
                  }

                  if (!$('.iconPercentage').hasClass('fa-arrow-up')) {
                    $('.iconPercentage').addClass('fa-arrow-up');
                    $('.iconPercentage').removeClass('fa-arrow-down');
                  }
                } else {
                  if (!$('.textPercentage').hasClass('text-danger')) {
                    $('.textPercentage').addClass('text-danger');
                    $('.textPercentage').removeClass('text-success');
                  }

                  if (!$('.iconPercentage').hasClass('fa-arrow-down')) {
                    $('.iconPercentage').addClass('fa-arrow-down');
                    $('.iconPercentage').removeClass('fa-arrow-up');
                  }
                }
                $('.lastMonthPercentage').text(data.sales.lastMonthSalesComparison + '%');
                $('.currentMonthSales').html('&#8369; '+data.sales.currentMonthSales);
              }
              if (data.visitors) {
                visitorsChart.data.labels = data.visitors.labels;
                visitorsChart.data.datasets[0].data = data.visitors.currentYearValues;
                visitorsChart.data.datasets[1].data = data.visitors.lastYearValues;
                visitorsChart.update();

                if (data.visitors.percentageVisitorsStatus) {
                  if (!$('.textVisitorPercentage').hasClass('text-success')) {
                    $('.textVisitorPercentage').addClass('text-success');
                    $('.textVisitorPercentage').removeClass('text-danger');
                  }

                  if (!$('.iconVisitorPercentage').hasClass('fa-arrow-up')) {
                    $('.iconVisitorPercentage').addClass('fa-arrow-up');
                    $('.iconVisitorPercentage').removeClass('fa-arrow-down');
                  }
                } else {
                  if (!$('.textVisitorPercentage').hasClass('text-danger')) {
                    $('.textVisitorPercentage').addClass('text-danger');
                    $('.textVisitorPercentage').removeClass('text-success');
                  }

                  if (!$('.iconVisitorPercentage').hasClass('fa-arrow-down')) {
                    $('.iconVisitorPercentage').addClass('fa-arrow-down');
                    $('.iconVisitorPercentage').removeClass('fa-arrow-up');
                  }
                }
                $('.lastMonthVisitorPercentage').text(data.visitors.lastMonthVisitorsComparison + '%');
                $('.currentMonthVisitors').text(data.visitors.currentMonthVisitors);
              }
          },
          error: function(xhr, status, error) {
              console.error('Error:', error);
          }
      }).always(function() {
          $('.sales-chart').find('.overlay').remove();
      });
    }

    var $visitorsChart = $('#visitors-chart')
    // eslint-disable-next-line no-unused-vars
    var visitorsChart = new Chart($visitorsChart, {
      type: 'line',
      data: {
        labels: [],
        datasets: [{
            label: 'Current Year',
            data: [],
            backgroundColor: '#17a2b8',
            borderColor: '#17a2b8',
            borderWidth: 1
        }, {
            label: 'Last Year',
            data: [],
            backgroundColor: '#6c757d',
            borderColor: '#6c757d',
            borderWidth: 1
        }]
      },
      options: {
        maintainAspectRatio: false,
        tooltips: {
          mode: mode,
          intersect: intersect
        },
        hover: {
          mode: mode,
          intersect: intersect
        },
        legend: {
          display: false
        },
        scales: {
          yAxes: [{
            // display: false,
            gridLines: {
              display: true,
              lineWidth: '4px',
              color: 'rgba(0, 0, 0, .2)',
              zeroLineColor: 'transparent'
            },
            ticks: $.extend({
              beginAtZero: true,
              suggestedMax: 200
            }, ticksStyle)
          }],
          xAxes: [{
            display: true,
            gridLines: {
              display: false
            },
            ticks: ticksStyle
          }]
        }
      }
    })
  // })

  // lgtm [js/unused-local-variable]
