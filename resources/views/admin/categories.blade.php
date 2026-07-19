<!DOCTYPE html>
<html>
  <head>

    @include('admin.css')
    <style>
        table{
           border: 1px solid skyblue;
           margin: auto;
           width: 700px;
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

            <h1>Manage Categories</h1>

            @if ($errors->any())
              <div class="alert alert-danger">
                <ul class="mb-0">
                  @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                  @endforeach
                </ul>
              </div>
            @endif

            @if(session('success'))
              <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form action="{{ url('add_category') }}" method="POST" style="margin-bottom:20px; text-align:center;">
                @csrf
                <input type="text" name="name" placeholder="Category name (e.g. Coffee)" required>

                <select name="parent_id">
                    <option value="">-- Main category --</option>
                    @foreach($topCategories as $top)
                        <option value="{{ $top->id }}">Under: {{ $top->name }}</option>
                    @endforeach
                </select>

                <button class="btn btn-primary" type="submit">Add Category</button>
            </form>

            <table>
                <tr><th>Category</th><th>Type</th><th>Action</th></tr>
                @foreach($topCategories as $top)
                    <tr>
                        <td><strong>{{ $top->name }}</strong></td>
                        <td>Main category</td>
                        <td><a href="{{ url('delete_category', $top->id) }}" onclick="return confirm('Delete this category?')">Delete</a></td>
                    </tr>
                    @foreach($top->children as $child)
                    <tr>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;↳ {{ $child->name }}</td>
                        <td>Sub-category of {{ $top->name }}</td>
                        <td><a href="{{ url('delete_category', $child->id) }}" onclick="return confirm('Delete this category?')">Delete</a></td>
                    </tr>
                    @endforeach
                @endforeach
            </table>

          </div>
        </div>
      </div>

    <!-- JavaScript files-->
    @include('admin.js')
  </body>
</html>