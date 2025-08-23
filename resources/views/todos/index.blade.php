@extends('layouts.app')

@section('title', '할일 목록 - TodoList')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">
        <i class="fas fa-clipboard-list text-primary me-2"></i>
        내 할일들
    </h2>
    <a href="{{ route('todos.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>새 할일 추가
    </a>
</div>

<!-- 통계 카드들 -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body">
                <h3 class="text-primary">{{ $todos->count() }}</h3>
                <p class="card-text text-muted">전체 할일</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body">
                <h3 class="text-success">{{ $todos->where('is_completed', true)->count() }}</h3>
                <p class="card-text text-muted">완료된 할일</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body">
                <h3 class="text-warning">{{ $todos->where('is_completed', false)->count() }}</h3>
                <p class="card-text text-muted">남은 할일</p>
            </div>
        </div>
    </div>
</div>

<!-- 필터 버튼들 -->
<div class="mb-4">
    <div class="btn-group" role="group">
        <button type="button" class="btn btn-outline-primary active" onclick="filterTodos('all')">
            <i class="fas fa-list me-1"></i>전체
        </button>
        <button type="button" class="btn btn-outline-warning" onclick="filterTodos('pending')">
            <i class="fas fa-clock me-1"></i>진행중
        </button>
        <button type="button" class="btn btn-outline-success" onclick="filterTodos('completed')">
            <i class="fas fa-check me-1"></i>완료
        </button>
    </div>
</div>

<!-- 할일 목록 -->
@if($todos->count() > 0)
    <div id="todos-container">
        @foreach($todos as $todo)
            <div class="todo-item {{ $todo->is_completed ? 'completed' : '' }}" data-status="{{ $todo->is_completed ? 'completed' : 'pending' }}">
                <div class="row align-items-center">
                    <!-- 체크박스 -->
                    <div class="col-auto">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox"
                                id="todo-{{ $todo->id }}"
                                {{ $todo->is_completed ? 'checked' : '' }}
                                onchange="toggleTodo({{ $todo->id }})">
                        </div>
                    </div>

                    <!-- 할일 내용 -->
                    <div class="col">
                        <h5 class="todo-title mb-1">{{ $todo->title }}</h5>
                        @if($todo->description)
                            <p class="text-muted mb-2">{{ $todo->description }}</p>
                        @endif
                        <small class="text-muted">
                            <i class="fas fa-calendar me-1"></i>
                            {{ $todo->created_at->format('Y-m-d H:i') }}
                        </small>
                    </div>

                    <!-- 액션 버튼들 -->
                    <div class="col-auto">
                        <div class="btn-group">
                            <a href="{{ route('todos.show', $todo) }}" class="btn btn-sm btn-outline-info" title="자세히 보기">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('todos.edit', $todo) }}" class="btn btn-sm btn-outline-warning" title="수정">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form method="POST" action="{{ route('todos.destroy', $todo) }}" class="d-inline"
                                onsubmit="return confirmDelete(this)">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="삭제">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@else
    <!-- 빈 상태 -->
    <div class="text-center py-5">
        <div class="mb-4">
            <i class="fas fa-clipboard-list fa-5x text-muted opacity-50"></i>
        </div>
        <h4 class="text-muted mb-3">아직 할일이 없어요!</h4>
        <p class="text-muted mb-4">첫 번째 할일을 추가해서 시작해보세요.</p>
        <a href="{{ route('todos.create') }}" class="btn btn-primary btn-lg">
            <i class="fas fa-plus me-2"></i>첫 할일 만들기
        </a>
    </div>
@endif

<!-- 플로팅 추가 버튼 (모바일용) -->
<button class="floating-add-btn d-md-none" onclick="location.href='{{ route('todos.create') }}'">
    <i class="fas fa-plus"></i>
</button>
@endsection

@section('scripts')
<script>
    // 필터링 기능
    function filterTodos(status) {
        const todos = document.querySelectorAll('.todo-item');
        const buttons = document.querySelectorAll('.btn-group .btn');

        // 버튼 상태 업데이트
        buttons.forEach(btn => btn.classList.remove('active'));
        event.target.classList.add('active');

        // 할일 필터링
        todos.forEach(todo => {
            const todoStatus = todo.dataset.status;
            if (status === 'all' || status === todoStatus) {
                todo.style.display = 'block';
                setTimeout(() => {
                    todo.style.opacity = '1';
                    todo.style.transform = 'translateX(0)';
                }, 50);
            } else {
                todo.style.opacity = '0';
                todo.style.transform = 'translateX(-20px)';
                setTimeout(() => {
                    todo.style.display = 'none';
                }, 300);
            }
        });
    }

    // 체크박스 토글 기능 (향후 AJAX로 구현)
    function toggleTodo(todoId) {
        // 현재는 페이지 새로고침으로 처리
        // 나중에 AJAX로 개선 예정
        const checkbox = document.getElementById('todo-' + todoId);
        const todoItem = checkbox.closest('.todo-item');

        // 임시로 시각적 피드백만 제공
        todoItem.style.opacity = '0.5';

        // 실제로는 서버에 AJAX 요청을 보내야 함
        console.log('Todo ' + todoId + ' 상태 변경 - AJAX 구현 예정');

        // 임시로 체크박스 상태만 유지
        setTimeout(() => {
            todoItem.style.opacity = '1';
        }, 500);
    }

    // 검색 기능 (향후 구현)
    function searchTodos() {
        // 검색 기능 구현 예정
        console.log('검색 기능 - 향후 구현 예정');
    }
</script>
@endsection
