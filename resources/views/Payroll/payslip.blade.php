<!DOCTYPE html>
<html>
<head>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f4f4f4;
    }
    .payslip {
      max-width: 500px;
      margin: 20px auto;
      background-color: #fff;
      padding: 20px;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }
    .header {
      text-align: center;
      background-color: #2ecc71;
      color: #fff;
      padding: 10px;
      margin-bottom: 20px;
    }
    .employee-details {
      margin-bottom: 20px;
    }
    .employee-details p {
      margin: 5px 0;
    }
    .table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 20px;
    }
    .table th,
    .table td {
      padding: 10px;
      border-bottom: 1px solid #ccc;
    }
    .table th {
      background-color: #2ecc71;
      color: #fff;
    }
    .totals {
      text-align: right;
    }
  </style>
</head>
<body>
  <div class="container" style="text-align: center">
        <h1>{{$spaName}}</h1>
  </div>
  <div class="payslip">
    <div class="header">
      <h2>Payslip</h2>
    </div>
    <div class="employee-details">
        <p><strong>Employee Name: {{$name}}</strong> </p>
        <p><strong>Employee ID: {{$id}}</strong> </p>
        <p><strong>Department: {{$role}}</strong>  </p>
    </div>
    <table class="table">
      <thead>
        <tr>
          <th>Description</th>
          <th>Amount ($)</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>Basic Salary</td>
          <td>{{$basicpay}}</td>
        </tr>
        <tr>
          <td>Allowance</td>
          <td>{{$allowance}}</td>
        </tr>
        <tr>
          <td>SSS</td>
          <td>{{$SSS}}</td>
        </tr>
        <tr>
            <td>PAGIBIG</td>
            <td>{{$PAGIBIG}}</td>
          </tr>
          <tr>
            <td>PHILHEALTH</td>
            <td>{{$PHILHEALTH}}</td>
          </tr>
          <tr>
            <td>Loan</td>
            <td>{{$loan}}</td>
          </tr>
      </tbody>
      <tfoot>
        <tr>
          <td><strong>NETPAY</strong></td>
          <td class="totals"><strong>{{$netpay}}</strong></td>
        </tr>
      </tfoot>
    </table>
    <div class="footer">
      <p>Thank you for your hard work!</p>
    </div>
  </div>
</body>
</html>
