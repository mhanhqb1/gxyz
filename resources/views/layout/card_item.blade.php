<article class="card">
    <a href="#">
        <img src="/imgs/1.jpg" alt="{{ $item->name }}" title="{{ $item->name }}"/>
        <div class="card-info">
            <h3>{{ $item->name }}</h3>
            <span>
                <ion-icon name="flame-outline"></ion-icon>
                Hot Images
            </span>
            <!-- <span>
                <ion-icon name="flame-outline"></ion-icon>
                {{ date('Y-m-d', strtotime($item->updated_at)) }}
            </span> -->
        </div>
    </a>
</article>
