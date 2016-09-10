<select class="form-control" id="language" name="language">
    @foreach(config('judge.languages') as $key => $language)
        <option value="{{ $language }}">{{ studly_case($language) }}</option>
    @endforeach
</select>
