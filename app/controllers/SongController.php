<?php
 error_reporting(E_ALL &~E_NOTICE);
class SongController extends \BaseController {


	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
		if(Input::has('name')) {
		$dj = Input::get('name');
		
		$songs = User::find($dj)->songs->toJson();
//DB::table('songs')->select('lat', 'long')->where('dj_id','=',Auth::id())->get();
		//return Response::json(
	//	$songs->toJson());

return $songs;
}
else {
$songs = User::find(Auth::id())->songs()->get();
$json='{
  "cols": [
        {"id":"","label":"Latitude","pattern":"","type":"number"},
        {"id":"","label":"Longitude","pattern":"","type":"number"},
      ],
  "rows": [';

foreach($songs as $song)
{
$json=$json.'{"c":[{"v":'.$song->lat.',"f":null},{"v":'.$song->long.',"f":null}]},';
}
$json=$json.']}';		

return $json;
	}
}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	$model = new Song;
	$model->title = Request::get('title');
	$model->artist = Request::get('artist');
	$model->requestor_name = Request::get('requestor_name');
	$dj = User::where('username','=',Request::get('dj_id'))->get();
	$model->dj_id = $dj[0]->id;
	$model->lat = Request::get('lat');
	$model->long = Request::get('long');
	//var_dump($model);exit();	
	if($model->save())
	{
	
	$response= Response::json(array(
	'requestor_name' => $model->requestor_name,
	'title' => $model->title,
	'artist'=>$model->artist,
	'lat'=>$model->lat,
	'long'=>$model->long),
	200
	);
	
	//Send message to DJ
	Larapush::send(['message' => $model->toJson()], [$dj[0]->username], 'song.request');
	Larapush::send(['message' => $model->requestor_name.' has sent a song requst of '. $model->title. ' by '. $model->artist . ' to DJ '. $dj[0]->username], ['demo'], 'generic.event');
	return $response;

//	$djname= $dj[0]->username;
//	return Redirect::to('/')->with('success',"$model->requestor_name, your song request of $model->title by $model->artist has been sent to DJ $djname ");
	} 
	else
	{

	return Response::json(array(
	'error' => true,
	'message' => 'There was an error. Please try again.'),
	400
	);

	//return Redirect::to('/')->with('success', 'There was an error, please try again!');
	}
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	$model = Song::findOrFail($id);
	return Response::json(array(
	'error' => false,
	'song' => $model->toArray()),
	200
	);
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	$model = Song::findOrFail($id);
	if($title = Request::get('title'))
	{
	$model->title = $title;
	}
	if($artist = Request::get('artist'))
	{
	$model->artist = $artist;
	}
	if($requestor_name = Request::get('requestor_name'))
	{
	$model->requestor_name = $requestor_name;
	}
	if($dj = Request::get('dj_id'))
	{
	$model->dj_id = $dj;
	}

	if($model->save())
	{
	return Response::json(array(
	'error' => false,
	'song' => $model->toArray()),
	200
	);
	}
	else
	{
	return Response::json(array(
	'error' => true,
	'message' => 'There was an error. Please try again.'),
	400
	);
	}
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	$model = Song::findOrFail($id);
	if($model->delete())
	{
	return Response::json(array(
	'error' => false,
	'song' => $model->toArray()),
	200
	);
	}
	else
	{
	return Response::json(array(
	'error' => true,
	'message' => 'There was an error. Please try again'),
	200
	);
	}
	}


}
