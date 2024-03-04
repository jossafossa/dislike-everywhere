<?php

use App\Models\Rating;

app()->get('/rate', function () {
  $url = request()->get('url');
  $rating = request()->get('rating');
  $response = Rating::rate($url, $rating);

  response()->json($response);
});


// app()->get('/ratings', function () {
//   $url = request()->get('url');
//   $ratings = Rating::get_ratings($url);

//   response()->json([
//     'ratings' => $ratings
//   ]);
// });

app()->get('/rating', function () {
  $url = request()->get('url');
  $rating = Rating::get_rating($url);

  response()->json($rating);
});
