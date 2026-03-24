@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/chat.css') }}">
@endsection

@section('content')
<div class="content">
  <!-- 左 -->
  <div class="left">
    <p class="text">その他の取引</p>
    @foreach($progressItems as $progressItem)
      <a href="/chat/{{ $progressItem->item->id }}" class="progress-item">
        <p class="progress-item__name">{{ $progressItem->item->name }}</p>
      </a>
    @endforeach
  </div>

  <!-- 右 -->
  <div class="right">
    <!-- 一段目 -->
    <div class="right-row">
      <div class="profile-thumb">
      @if ($assessmentChat && $assessmentChat->seller && $assessmentChat->seller->profile_img)
        <img id="profilePreview" src="{{ asset('storage/profiles/' . $item->seller->profile_img) }}" alt="" />
      @else
        <img id="profilePreview" src="" alt="" />
      @endif
      </div>
      <!-- ユーザー名 -->
      <span class="profile-name">
        「{{ $tradingUser }} 」さんとの取引画面
      </span>
      <!-- 購入者の場合のみボタンを表示する -->
      @if($assessmentChat && Auth::id() === $assessmentChat->buyer_user_id)
        <button type="button" id="openCompletedModal" class="complete-btn">
        取引を完了する
        </button>
      @endif
    </div>

    <!-- 二段目 -->
    <div class="right-row2">
      <div class="item-img__inner">
        <img class="item-img" src="{{ asset('storage/items/' . $item->item_img) }}" alt="商品画像" />
      </div>
      <div class="right-row__name-price">
        <h1 class="item-name">{{ $item->name }}</h1>
        <p class="item-price">￥ {{ number_format($item->price) }}</p>
      </div>
    </div>

    <!-- 三段目 -->
    <div class="right-row3">
      @if($chats->isNotEmpty())
        @foreach ($chats as $chat)
          @if ($chat->sender_user_id === $user->id)
            <!-- 自分のメッセージ -->
            <div class="profile-thumb__mine">
              <div class="profile-thumb__name">
                <!-- ユーザー名 -->
                <span class="message-name">
                  {{ $chat->sender->name }}
                </span>
                <div class="profile-thumb">
                  @if ($chat->sender->profile_img)
                    <img src="{{ asset('storage/profiles/' . $chat->sender->profile_img) }}" alt="" />
                  @else
                    <img src="" alt="" />
                  @endif
                </div>
              </div>
              
              @if(request('edit') == $chat->id)
                <!--メッセージの編集 -->
                <form action="/item/{{ $item->id }}/chat/update" method="post">
                  @csrf
                  @method('PATCH')
                  <input type="hidden" name="id" value="{{ $chat->id }}">

                  <input type="text" name="chat" value="{{ old('chat', $chat->chat) }}" class="edit-input">
                  <div class="form__error">
                    @error('chat')
                      {{ $message }}
                    @enderror
                  </div>

                  <button type="submit" class="update">更新</button>
                </form>
              @else
                <!-- メッセージの表示のみ -->
                <div class="message">{{ $chat->chat }}</div>
                <div class="edit-delete__btn">
                  <form action="/chat/{{ $item->id }}" method="get" style="display:inline;">
                    <input type="hidden" name="edit" value="{{ $chat->id }}">
                    <button type="submit" class="edit">編集</button>
                  </form>

                  <form action="/item/{{$item->id}}/chat/delete" method="post" class="">
                    @method('DELETE')
                    @csrf
                    <input type="hidden" name="id" value="{{ $chat->id }}">
                    <button  class="delete"> 削除 </button>
                  </form>
                </div>
              @endif
            </div>
          @else
            <!-- 取引相手のメッセージ -->
            <div class="profile-thumb__partner">
              <div class="profile-thumb__name">
                <div class="profile-thumb">
                  @if ($chat->sender->profile_img)
                    <img src="{{ asset('storage/profiles/' . $chat->sender->profile_img) }}" alt="" />
                  @else
                    <img src="" alt="" />
                  @endif
                </div>
                <!-- ユーザー名 -->
                <span class="message-name">
                  {{ $chat->sender->name }}
                </span>
              </div>
              <div class="message"> {{$chat->chat}} </div>
            </div>
          @endif
        @endforeach
      @endif
    </div>

    <!-- チャットを送る -->
    <form action="/item/{{$item->id}}/chat" method="post" class="send-message" enctype="multipart/form-data">
      @csrf
      <div class="message-validate">
      <textarea
        name="message"
        class="send-message__input"
        placeholder="取引メッセージを記入してください">{{ old('message') }}</textarea>
        <div class="form__error">
          @error('message')
            {{ $message }}
          @enderror
        </div>
      </div>

      <label for="item_img" class="img-btn">
        画像を選択する
      </label>
      <!-- 画像が選択されたらすぐに表示 -->
      <input id="item_img" type="file" name="item_img" accept="image/*" class="item-file">
      <div class="form__error">
        @error('item_img')
          {{ $message }}
        @enderror
      </div>

      <!-- 送信アイコン -->
      <button type="submit" class="send-message__btn">
        <img src="{{ asset('images/send_message.svg') }}" alt="send_message" class="send-message__icon">
      </button>
    </form>
  </div>
</div>

<!-- 評価用モーダル -->
<div id="completedModal" class="modal-overlay" style="display: {{ !empty($shouldOpenAssessmentModal) ? 'flex' : 'none' }};">
  <div class="modal-content">
    @if($assessmentChat && Auth::id() === $assessmentChat->buyer_user_id)
      <button type="button" id="closeModal" class="modal-close-btn">×</button>
    @endif

    <p class="modal-title">取引が完了しました。</p>
    <p class="modal-subtitle">今回の取引相手はどうでしたか？</p>

    <form action="{{ route('item.completed', ['item' => $item->id]) }}" method="POST">
      @csrf
      @method('PATCH')

      <div class="star-area" id="starArea">
        <span class="star" data-value="1">★</span>
        <span class="star" data-value="2">★</span>
        <span class="star" data-value="3">★</span>
        <span class="star" data-value="4">★</span>
        <span class="star" data-value="5">★</span>
      </div>

      <input type="hidden" name="score" id="scoreInput" value="0">

      <button type="submit">送信する</button>
    </form>
  </div>
</div>

<!-- モーダルを開く -->
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const openBtn = document.getElementById('openCompletedModal');
    const modal = document.getElementById('completedModal');
    const closeBtn = document.getElementById('closeModal');

    // 購入者のみボタン押下で開く
    if (openBtn && modal) {
      openBtn.addEventListener('click', function () {
        modal.style.display = 'flex';
      });
    }

    //  buyer のときだけ閉じられる
    @if($assessmentChat && Auth::id() === $assessmentChat->buyer_user_id)
      if (closeBtn && modal) {
        closeBtn.addEventListener('click', function () {
          modal.style.display = 'none';
        });
      }

      if (modal) {
        modal.addEventListener('click', function (e) {
          if (e.target === modal) {
            modal.style.display = 'none';
          }
        });
      }
    @endif
  });
</script>

<!-- モーダル上での評価 -->
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const stars = document.querySelectorAll('.star');
    const scoreInput = document.getElementById('scoreInput');

    stars.forEach(star => {
      star.addEventListener('click', function () {
        const value = this.dataset.value;

        scoreInput.value = value;

        stars.forEach(s => s.classList.remove('active'));

        stars.forEach(s => {
          if (Number(s.dataset.value) <= Number(value)) {
            s.classList.add('active');
          }
        });
      });
    });
  });
</script>

<!-- 画面を遷移しても未送信のメッセージ分は残す -->
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const input = document.querySelector('.send-message__input');

    // ★保存
    input.addEventListener('input', function () {
      localStorage.setItem('chat_message_{{ $assessmentChat->id ?? "default" }}', input.value);
    });

    // ★復元
    const saved = localStorage.getItem('chat_message_{{ $assessmentChat->id ?? "default" }}');
    if (saved) {
      input.value = saved;
    }
  });
</script>

<!-- 未送信のまま画面遷移したときに残しておいたメッセージを送信後は消す -->
<script>
  document.querySelector('.send-message').addEventListener('submit', function () {
    localStorage.removeItem('chat_message_{{ $assessmentChat->id ?? "default" }}');
  });
</script>
@endsection