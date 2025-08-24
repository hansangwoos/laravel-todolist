@extends('layouts.app')

@section('title',"상세보기 - TodoList")

@section('content')

<div class="row">
    <!-- 메인 콘텐츠 -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <div class="form-check me-3">
                        <input class="form-check-input" type="checkbox"
                               id="todo-status"
                               {{ $todo->is_completed ? 'checked' : '' }}
                               onchange="toggleTodo({{ $todo->id }})">
                    </div>
                    <div>
                        <h4 class="mb-0 {{ $todo->is_completed ? 'text-decoration-line-through text-muted' : '' }}">
                            {{ $todo->title }}
                        </h4>
                        <small class="text-muted">
                            <i class="fas fa-calendar me-1"></i>
                            {{ $todo->created_at->format('Y년 m월 d일 H:i') }}에 생성
                        </small>
                    </div>
                </div>

                <!-- 상태 배지 -->
                <span class="badge {{ $todo->is_completed ? 'bg-success' : 'bg-warning' }} fs-6">
                    <i class="fas {{ $todo->is_completed ? 'fa-check-circle' : 'fa-clock' }} me-1"></i>
                    {{ $todo->is_completed ? '완료됨' : '진행중' }}
                </span>
            </div>

            <div class="card-body">
                @if($todo->description)
                    <div class="mb-4">
                        <h6 class="text-muted mb-2">
                            <i class="fas fa-align-left me-2"></i>상세 설명
                        </h6>
                        <div class="p-3 bg-light rounded">
                            {!! nl2br(e($todo->description)) !!}
                        </div>
                    </div>
                @endif

                <!-- 진행 상황 -->
                <div class="mb-4">
                    <h6 class="text-muted mb-2">
                        <i class="fas fa-chart-pie me-2"></i>진행 상황
                    </h6>
                    <div class="progress" style="height: 20px;">
                        <div class="progress-bar {{ $todo->is_completed ? 'bg-success' : 'bg-warning' }}"
                             role="progressbar"
                             style="width: {{ $todo->is_completed ? '100' : '50' }}%">
                            {{ $todo->is_completed ? '100% 완료' : '50% 진행중' }}
                        </div>
                    </div>
                </div>

                <!-- 액션 버튼들 -->
                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('todos.edit', $todo) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-2"></i>수정
                    </a>

                    <form method="POST" action="{{ route('todos.destroy', $todo) }}" class="d-inline"
                          onsubmit="return confirmDelete(this)">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash me-2"></i>삭제
                        </button>
                    </form>

                    <button class="btn btn-info" onclick="shareTodo()">
                        <i class="fas fa-share me-2"></i>공유
                    </button>

                    <a href="{{ route('todos.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>목록으로
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- 사이드바 정보 -->
    <div class="col-lg-4">
        <!-- 상세 정보 카드 -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>상세 정보
                </h6>
            </div>
            <div class="card-body">
                <div class="info-item mb-3">
                    <strong class="text-muted">생성일:</strong>
                    <div>{{ $todo->created_at->format('Y-m-d H:i:s') }}</div>
                    <small class="text-muted">{{ $todo->created_at->diffForHumans() }}</small>
                </div>

                <div class="info-item mb-3">
                    <strong class="text-muted">마지막 수정:</strong>
                    <div>{{ $todo->updated_at->format('Y-m-d H:i:s') }}</div>
                    <small class="text-muted">{{ $todo->updated_at->diffForHumans() }}</small>
                </div>

                <div class="info-item mb-3">
                    <strong class="text-muted">상태:</strong>
                    <div>
                        <span class="badge {{ $todo->is_completed ? 'bg-success' : 'bg-warning' }}">
                            {{ $todo->is_completed ? '완료' : '진행중' }}
                        </span>
                    </div>
                </div>

                <div class="info-item">
                    <strong class="text-muted">소요 시간:</strong>
                    <div>
                        @if($todo->is_completed)
                            {{ $todo->created_at->diffInHours($todo->updated_at) }}시간
                        @else
                            {{ $todo->created_at->diffInHours(now()) }}시간 경과
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- 관련 액션 카드 -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-bolt me-2"></i>빠른 액션
                </h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <button class="btn btn-outline-primary btn-sm" onclick="duplicateTodo()">
                        <i class="fas fa-copy me-2"></i>복사해서 새로 만들기
                    </button>

                    <button class="btn btn-outline-success btn-sm" onclick="exportTodo()">
                        <i class="fas fa-download me-2"></i>텍스트로 내보내기
                    </button>

                    <button class="btn btn-outline-info btn-sm" onclick="printTodo()">
                        <i class="fas fa-print me-2"></i>인쇄하기
                    </button>
                </div>
            </div>
        </div>

        <!-- 통계 카드 -->
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-chart-bar me-2"></i>나의 할일 통계
                </h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-4">
                        <div class="border-end">
                            <h5 class="text-primary mb-0">{{ \App\Models\Todo::count() }}</h5>
                            <small class="text-muted">전체</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="border-end">
                            <h5 class="text-success mb-0">{{ \App\Models\Todo::where('is_completed', true)->count() }}</h5>
                            <small class="text-muted">완료</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <h5 class="text-warning mb-0">{{ \App\Models\Todo::where('is_completed', false)->count() }}</h5>
                        <small class="text-muted">진행중</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // 상태 토글
    function toggleTodo(todoId) {
        const checkbox = document.getElementById('todo-status');
        const title = document.querySelector('.card-header h4');
        const badge = document.querySelector('.badge');
        const progressBar = document.querySelector('.progress-bar');

        // 시각적 피드백
        checkbox.disabled = true;

        // 실제로는 AJAX로 서버에 요청해야 함
        setTimeout(() => {
            const isCompleted = checkbox.checked;

            // 제목 스타일 변경
            if (isCompleted) {
                title.classList.add('text-decoration-line-through', 'text-muted');
                badge.className = 'badge bg-success fs-6';
                badge.innerHTML = '<i class="fas fa-check-circle me-1"></i>완료됨';
                progressBar.className = 'progress-bar bg-success';
                progressBar.style.width = '100%';
                progressBar.textContent = '100% 완료';
            } else {
                title.classList.remove('text-decoration-line-through', 'text-muted');
                badge.className = 'badge bg-warning fs-6';
                badge.innerHTML = '<i class="fas fa-clock me-1"></i>진행중';
                progressBar.className = 'progress-bar bg-warning';
                progressBar.style.width = '50%';
                progressBar.textContent = '50% 진행중';
            }

            checkbox.disabled = false;

            // 성공 메시지 표시
            showNotification(isCompleted ? '할일을 완료했습니다!' : '할일을 진행중으로 변경했습니다!', 'success');
        }, 500);
    }

    // 할일 복사
    function duplicateTodo() {
        const title = '{{ $todo->title }}';
        const description = `{{ $todo->description }}`;

        if (confirm(`"${title}" 할일을 복사해서 새로 만드시겠습니까?`)) {
            // 실제로는 서버에 POST 요청
            window.location.href = `{{ route('todos.create') }}?duplicate=true&title=${encodeURIComponent(title)}&description=${encodeURIComponent(description)}`;
        }
    }

    // 할일 공유
    function shareTodo() {
        const title = '{{ $todo->title }}';
        const url = window.location.href;

        if (navigator.share) {
            navigator.share({
                title: title,
                text: '내 할일을 확인해보세요!',
                url: url
            });
        } else {
            // 클립보드에 복사
            navigator.clipboard.writeText(url).then(() => {
                showNotification('링크가 클립보드에 복사되었습니다!', 'info');
            });
        }
    }

    // 텍스트 내보내기
    function exportTodo() {
        const title = '{{ $todo->title }}';
        const description = `{{ $todo->description }}`;
        const status = {{ $todo->is_completed ? 'true' : 'false' }};
        const createdAt = '{{ $todo->created_at->format("Y-m-d H:i") }}';

        const text = `할일: ${title}\n\n상세설명:\n${description}\n\n상태: ${status ? '완료' : '진행중'}\n생성일: ${createdAt}`;

        const blob = new Blob([text], { type: 'text/plain' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `${title}.txt`;
        a.click();
        URL.revokeObjectURL(url);
    }

    // 인쇄
    function printTodo() {
        window.print();
    }

    // 알림 표시
    function showNotification(message, type = 'info') {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
        alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        alertDiv.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;

        document.body.appendChild(alertDiv);

        // 3초 후 자동 제거
        setTimeout(() => {
            if (alertDiv.parentNode) {
                alertDiv.remove();
            }
        }, 3000);
    }

    // 키보드 단축키
    document.addEventListener('keydown', function(e) {
        // E키로 수정
        if (e.key === 'e' || e.key === 'E') {
            if (!e.target.matches('input, textarea')) {
                window.location.href = '{{ route("todos.edit", $todo) }}';
            }
        }

        // Space로 상태 토글
        if (e.key === ' ') {
            if (!e.target.matches('input, textarea, button')) {
                e.preventDefault();
                const checkbox = document.getElementById('todo-status');
                checkbox.checked = !checkbox.checked;
                toggleTodo({{ $todo->id }});
            }
        }

        // Backspace로 뒤로가기
        if (e.key === 'Backspace') {
            if (!e.target.matches('input, textarea')) {
                window.location.href = '{{ route("todos.index") }}';
            }
        }
    });
</script>

<!-- 인쇄용 스타일 -->
<style media="print">
    .btn, .card-header .badge, nav, .floating-add-btn {
        display: none !important;
    }

    .card {
        border: 1px solid #000 !important;
        box-shadow: none !important;
    }

    .card-body {
        padding: 1rem !important;
    }

    body {
        background: white !important;
    }
</style>
@endsection
