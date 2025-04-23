<x-guest-layout>
    <style>
        .blurred-box {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            color: white;
        }

        .castle-option {
            border: 2px solid rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 15px;
            transition: all 0.2s ease;
            cursor: pointer;
            position: relative;
        }

        .castle-option:hover,
        .castle-option input:checked+label {
            background-color: rgba(255, 255, 255, 0.2);
            border-color: #17a2b8;
        }

        .castle-option input[type="radio"] {
            position: absolute;
            opacity: 0;
            cursor: pointer;
        }

        .castle-option label {
            margin: 0;
            font-size: 1.1rem;
            cursor: pointer;
            display: block;
        }
    </style>

    <div class="container py-10">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="blurred-box">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4>{{ __('pages.select_your_castle') }}</h4>
                        <a href="{{ route('game-data.edit', ['fresh' => true]) }}" class="btn btn-success">
                            + {{ __('pages.add_new_castle') }}
                        </a>
                    </div>

                    <form id="castleForm" action="{{ route('game-data.select_castle') }}" method="POST">
                        @csrf

                        @foreach ($castles as $castle)
                        <div class="castle-option">
                            <input type="radio" id="castle_{{ $castle->id }}" name="castle_id" value="{{ $castle->id }}">
                            <label for="castle_{{ $castle->id }}">
                                {{ $castle->castle_name }} - {{ __('pages.level') }} {{ $castle->castle_level }}
                            </label>
                        </div>
                        @endforeach
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Submit the form as soon as a castle is selected
        document.querySelectorAll('input[name="castle_id"]').forEach(radio => {
            radio.addEventListener('change', function() {
                document.getElementById('castleForm').submit();
            });
        });
    </script>
</x-guest-layout>