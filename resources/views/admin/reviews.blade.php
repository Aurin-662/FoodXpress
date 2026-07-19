<!DOCTYPE html>
<html>
  <head>
    @include('admin.css')
  </head>
  <body>
    @include('admin.header')
    @include('admin.sidebar')
    <div class="page-content">
      <div class="page-header">
        <div class="container-fluid">
          <h1>Customer Reviews</h1>

          @if($reviews->isEmpty())
              <div class="alert alert-secondary">No reviews have been submitted yet.</div>
          @else
          <table class="table table-dark table-striped">
            <thead>
              <tr>
                <th>Food</th>
                <th>User</th>
                <th>Rating</th>
                <th>Comment</th>
                <th>Submitted</th>
              </tr>
            </thead>
            <tbody>
              @foreach($reviews as $review)
                <tr>
                  <td>{{ $review->food->title ?? 'Deleted food' }}</td>
                  <td>{{ $review->user->name ?? 'Deleted user' }}</td>
                  <td>{{ $review->rating }} ⭐</td>
                  <td>{{ $review->comment ?: '—' }}</td>
                  <td>{{ $review->created_at->format('Y-m-d H:i') }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
          @endif
        </div>
      </div>
    </div>
    @include('admin.js')
  </body>
</html>
