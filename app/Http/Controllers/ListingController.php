<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ListingController extends Controller
{
  public function index(Request $request)
  {

    // $page = $request->query('page', 1);
    // $perPage = $request->query('perPage', 1);

    // $offset = ($page - 1) * $perPage;

    $listings = Listing::with('user', 'wishlist')
      ->latest()
    // ->skip($offset)
    // ->take($perPage)
      ->get();

    // Check if this page's result set is the last one
    // $totalRecords = Listing::count();
    // $lastPage = ceil($totalRecords / $perPage);

    return response([
      'listings' => $listings,
      // 'isLastPage' => $page >= $lastPage,
    ], 200);
  }

  public function show($id)
  {
    $listing = Listing::where('id', $id)->with('user', 'wishlist')->latest()->get();

    return response([
      'listing' => $listing,
    ], 200);
  }

  public function edit($id)
  {
    $listing = Listing::where('id', $id)->with('user', 'wishlist')->latest()->first();

    return response([
      'listing' => $listing,
    ], 200);
  }

  public function user_listing($id)
  {
    $listings = Listing::where('user_id', $id)->with('user', 'wishlist')->latest()->get();

    return response([
      'listings' => $listings,
    ], 200);
  }

  //save listing to the database
  public function store(Request $request)
  {
    $attr = $request->validate([
      'title' => ['required'],
      'category' => 'required',
      'price' => ['required', 'regex:/^\d+(\.\d+)?$/'],
      'bedroom' => 'required',
      'bathroom' => 'required',
      'land_size' => 'required',
      'garage' => 'required',
      'address' => 'required',
      'about' => 'required',
      'image.*' => ['required', 'image', 'mimes:jpeg,png,jpg,webp'],

    ]);

    $attr['user_id'] = auth()->id();
    $images = [];

    // Image Upload
    if ($request->hasFile('image')) {
      foreach ($request->file('image') as $image) {
        $imgName = time() . '_' . Str::random(10) . '.' . $image->extension();
        $destinationPath = public_path('/uploads');
        $image->move($destinationPath, $imgName);
        $images[] = $imgName;
      }
    }

    //insert image as a comma separated and insert into the database
    $attr['image'] = implode(', ', $images);

    $listing = Listing::create($attr);

    return response([
      'message' => 'listing created successfully',
      'listing' => $listing,
    ], 200);
  }

  public function update(Request $request, Listing $listing)
  {

    $attr = $request->validate([
      'title' => ['required'],
      'category' => 'required',
      'price' => ['required', 'regex:/^\d+(\.\d+)?$/'],
      'bedroom' => 'required',
      'bathroom' => 'required',
      'land_size' => 'required',
      'garage' => 'required',
      'address' => 'required',
      'about' => 'required',
      'image.*' => ['required', 'image', 'mimes:jpeg,png,jpg,webp'],

    ]);

    if (!$listing) {
      return response([
        'message' => 'Listing not found',
      ], 403);
    }

    //check if listing belong to user
    if ($listing->user_id != auth()->id()) {
      return response([
        'message' => 'Permission denied',
      ], 403);
    }

    $attr['user_id'] = auth()->id();

    // $oldImagePath = $listing->image;

    $images = [];

    // Image Upload
    if ($request->hasFile('image')) {
      foreach ($request->file('image') as $image) {
        $imgName = time() . '_' . Str::random(10) . '.' . $image->extension();
        $destinationPath = public_path('/uploads');
        $image->move($destinationPath, $imgName);
        $images[] = $imgName;
      }
    }

    //insert image as a comma separated and insert into the database
    $attr['image'] = implode(', ', $images);

    //update listing
    $listing->update($attr);

    return response([
      'message' => 'Listing updated',
      'listing' => $listing,
    ], 200);
  }

  public function search(Request $request)
  {
    $search = $request->q;

    $listings = Listing::where('address', 'like', '%' . $search . '%')
      ->with('user', 'wishlist')
      ->latest()
      ->get();

    return response([
      'message' => 'Successful',
      'listings' => $listings,
    ], 200);
  }

  //delete
  public function destroy($id)
  {

    //find the listing
    $listing = Listing::find($id);

    if (!$listing) {
      return response([
        'message' => 'Listing not found',
      ], 403);
    }

    //check if listing belong to user
    if ($listing->user_id != auth()->id()) {
      return response([
        'message' => 'Permission denied',
      ], 403);
    }

    $listing->delete();

    return response([
      'message' => 'Listing deleted.',
      'id' => $id,
    ], 200);
  }
}
