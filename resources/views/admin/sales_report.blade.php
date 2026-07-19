<!DOCTYPE html>
<html>
  <head>

    @include('admin.css')
    <style>
        table{
           border: 1px solid skyblue;
           margin: auto;
           width: 900px;
        }
        th{
            background: skyblue;
            color: white;
            padding: 10px;
        }
        td{
            color: white;
            padding: 10px;
        }
    </style>
  </head>
  <body>

    @include('admin.header')

    @include('admin.sidebar')

      <div class="page-content">
        <div class="page-header">
          <div class="container-fluid">

            <h1>Sales Report</h1>

            <h3 style="text-align:center; color:white;">Best Selling Foods </h3>
            <table>
              <tr>
                <th>Food</th>
                <th>Total Quantity Sold</th>
                <th>Total Revenue</th>
              </tr>
              @foreach($bestSelling as $row)
              <tr>
                <td>{{ $row->title }}</td>
                <td>{{ $row->total_qty }}</td>
                <td>${{ $row->total_revenue }}</td>
              </tr>
              @endforeach
            </table>

            <br>

            <h3 style="text-align:center; color:white;">Daily Revenue</h3>
            <table>
              <tr>
                <th>Date</th>
                <th>Total Revenue</th>
              </tr>
              @foreach($dailyRevenue as $row)
              <tr>
                <td>{{ $row->order_date }}</td>
                <td>${{ $row->total }}</td>
              </tr>
              @endforeach
            </table>

            <br>

            <h3 style="text-align:center; color:white;">Customer Orders </h3>
            <table>
              <tr>
                <th>Customer</th>
                <th>Email</th>
                <th>Food</th>
                <th>Price</th>
                <th>Status</th>
                <th>Date</th>
              </tr>
              @foreach($customerOrders as $row)
              <tr>
                <td>{{ $row->name }}</td>
                <td>{{ $row->email }}</td>
                <td>{{ $row->title }}</td>
                <td>${{ $row->price }}</td>
                <td>{{ $row->delivery_status }}</td>
                <td>{{ $row->created_at }}</td>
              </tr>
              @endforeach
            </table>

          </div>
        </div>
      </div>

    <!-- JavaScript files-->
    @include('admin.js')
  </body>
</html>