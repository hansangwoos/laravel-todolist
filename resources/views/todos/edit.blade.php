@extends('layouts.app')

@section('title', '할일 수정 - TodoList')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">
                    <i class="fas fa-plus-circle me-2"></i>할일 수정
                </h4>
                <small class="text-muted">
                    {{ $todo->created_at->format('Y-m-d H:i') }}에 생성된 할일
                </small>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('todos.update', $todo) }}">
                    @csrf
                    @method('PUT')

                    <!-- 상태 토글 -->
                    <div class="mb-4">
                        <div class="card bg-light">
                            <div class="card-body p-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input"
                                        type="checkbox"
                                        id="is_completed"
                                        name="is_completed"
                                        value="1"
                                        {{ $todo->is_completed ? 'checked' : '' }}
                                        onchange="updateStatus(this)">
                                    <label class="form-check-label fw-bold" for="is_completed" id="status-label">
                                        <i class="fas {{ $todo->is_completed ? 'fa-check-circle text-success' : 'fa-clock text-warning' }} me-2"></i>
                                        <span id="status-text">{{ $todo->is_completed ? '완료됨' : '진행중' }}</span>
                                    </label>
                                </div>
                                <small class="text-muted">스위치를 클릭해서 완료 상태를 변경하세요</small>
                            </div>
                        </div>
                    </div>

                    <!-- 제목 입력 -->
                    <div class="mb-4">
                        <label for="title" class="form-label fw-bold">
                            <i class="fas fa-pencil-alt text-primary me-2"></i>할일 제목 *
                        </label>
                        <input type="text"
                            class="form-control @error('title') is-invalid @enderror"
                            id="title"
                            name="title"
                            value="{{ old('title', $todo->title) }}"
                            required>
                        @error('title')
                            <div class="invalid-feedback">
                                <i class="fas fa-exclamation-triangle me-1"></i>
                                {{ $message }}
                            </div>
                        @enderror
                        <div class="form-text text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            명확하고 실행 가능한 제목을 작성해주세요.
                        </div>
                    </div>

                    <!-- 상세 설명 입력 -->
                    <div class="mb-4">
                        <label for="description" class="form-label fw-bold">
                            <i class="fas fa-align-left text-primary me-2"></i>상세 설명
                        </label>
                        <textarea class="form-control @error('description') is-invalid @enderror"
                                id="description"
                                name="description"
                                rows="4"
                                >{{ old("description", $todo->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">
                                <i class="fas fa-exclamation-triangle me-1"></i>
                                {{ $message }}
                            </div>
                        @enderror
                        <div class="form-text text-muted">
                            <i class="fas fa-lightbulb me-1"></i>
                            나중에 참고할 수 있도록 세부사항을 적어보세요.
                        </div>
                    </div>

                    <!-- 변경 사항 미리보기 -->
                    <div class="mb-4" id="changes-preview" style="display: none;">
                        <label class="form-label fw-bold">
                            <i class="fas fa-eye text-warning me-2"></i>변경 사항 미리보기
                        </label>
                        <div class="card border-warning">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6 class="text-muted">이전</h6>
                                        <div class="p-2 bg-light rounded">
                                            <div class="original-title">{{ $todo->title }}</div>
                                            <small class="text-muted original-description">{{ $todo->description ?: '설명 없음' }}</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <h6 class="text-warning">수정 후</h6>
                                        <div class="p-2 bg-warning bg-opacity-10 rounded">
                                            <div class="new-title">{{ $todo->title }}</div>
                                            <small class="text-muted new-description">{{ $todo->description ?: '설명 없음' }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 수정 이력 -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">
                            <i class="fas fa-history text-warning me-2"></i>수정 이력
                        </label>
                        <div class="card bg-light">
                            <div class="card-body">
                                <div class="d-flex justify-content-between text-muted small">
                                    <span>
                                        <i class="fas fa-plus-circle me-1"></i>
                                        생성: {{ $todo->created_at->format('Y-m-d H:i') }}
                                    </span>
                                    <span>
                                        <i class="fas fa-edit me-1"></i>
                                        마지막 수정: {{ $todo->updated_at->format('Y-m-d H:i') }}
                                    </span>
                                </div>
                                @if($todo->created_at->eq($todo->updated_at))
                                    <div class="text-center text-muted small mt-2">
                                        <i class="fas fa-info-circle me-1"></i>
                                        아직 수정된 적이 없습니다
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- 버튼 그룹 -->
                    <!-- 버튼 그룹 -->
                    <div class="d-grid gap-2 d-md-flex justify-content-md-between">
                        <div>
                            <a href="{{ route('todos.show', $todo) }}" class="btn btn-outline-info me-md-2">
                                <i class="fas fa-eye me-2"></i>상세보기
                            </a>
                            <a href="{{ route('todos.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-list me-2"></i>목록
                            </a>
                        </div>
                        <div>
                            <button type="button" class="btn btn-outline-danger me-md-2" onclick="resetForm()">
                                <i class="fas fa-undo me-2"></i>초기화
                            </button>
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-save me-2"></i>수정 저장
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- 도움말 카드 -->
        <div class="card mt-4">
            <div class="card-header bg-info text-white">
                <h6 class="mb-0">
                    <i class="fas fa-question-circle me-2"></i>할일 작성 팁
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-success">
                            <i class="fas fa-check me-2"></i>좋은 예시
                        </h6>
                        <ul class="list-unstyled text-muted small">
                            <li><i class="fas fa-star me-2 text-warning"></i>"Laravel TodoList 프로젝트 완성하기"</li>
                            <li><i class="fas fa-star me-2 text-warning"></i>"오늘 오후 3시까지 보고서 제출"</li>
                            <li><i class="fas fa-star me-2 text-warning"></i>"주말에 운동 30분하기"</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-danger">
                            <i class="fas fa-times me-2"></i>피해야 할 예시
                        </h6>
                        <ul class="list-unstyled text-muted small">
                            <li><i class="fas fa-minus me-2 text-muted"></i>"공부하기" (너무 모호함)</li>
                            <li><i class="fas fa-minus me-2 text-muted"></i>"정리" (구체적이지 않음)</li>
                            <li><i class="fas fa-minus me-2 text-muted"></i>"나중에 할 것들" (실행하기 어려움)</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // 원본 데이터 저장
    const originalData = {
        title: '{{ $todo->title }}',
        description: `{{ $todo->description }}`,
        is_completed: {{ $todo->is_completed ? 'true' : 'false' }}
    };

    let hasChanges = false;

    // 상태 업데이트
    function updateStatus(checkbox) {
        const icon = document.querySelector('#status-label i');
        const text = document.getElementById('status-text');

        if (checkbox.checked) {
            icon.className = 'fas fa-check-circle text-success me-2';
            text.textContent = '완료됨';
        } else {
            icon.className = 'fas fa-clock text-warning me-2';
            text.textContent = '진행중';
        }

        checkChanges();
    }

    // 변경 사항 확인
    function checkChanges() {
        const currentTitle = document.getElementById('title').value;
        const currentDescription = document.getElementById('description').value;
        const currentCompleted = document.getElementById('is_completed').checked;

        hasChanges = (
            currentTitle !== originalData.title ||
            currentDescription !== originalData.description ||
            currentCompleted !== originalData.is_completed
        );

        // 변경 사항 미리보기 업데이트
        updatePreview(currentTitle, currentDescription);

        // 변경 사항 미리보기 표시/숨김
        const preview = document.getElementById('changes-preview');
        if (hasChanges && (currentTitle !== originalData.title || currentDescription !== originalData.description)) {
            preview.style.display = 'block';
        } else {
            preview.style.display = 'none';
        }

        // 저장 버튼 상태 업데이트
        const saveBtn = document.querySelector('button[type="submit"]');
        if (hasChanges) {
            saveBtn.classList.remove('btn-warning');
            saveBtn.classList.add('btn-success');
            saveBtn.innerHTML = '<i class="fas fa-save me-2"></i>변경사항 저장';
        } else {
            saveBtn.classList.remove('btn-success');
            saveBtn.classList.add('btn-warning');
            saveBtn.innerHTML = '<i class="fas fa-save me-2"></i>수정 저장';
        }
    }

    // 미리보기 업데이트
    function updatePreview(title, description) {
        document.querySelector('.new-title').textContent = title || '제목 없음';
        document.querySelector('.new-description').textContent = description || '설명 없음';
    }

    // 폼 초기화
    function resetForm() {
        if (confirm('모든 변경사항을 취소하고 원래 상태로 되돌리시겠습니까?')) {
            document.getElementById('title').value = originalData.title;
            document.getElementById('description').value = originalData.description;
            document.getElementById('is_completed').checked = originalData.is_completed;

            updateStatus(document.getElementById('is_completed'));
            checkChanges();

            // 로컬스토리지 클리어
            localStorage.removeItem('todo_edit_{{ $todo->id }}');
        }
    }

    // 자동 저장 기능
    function autoSave() {
        const data = {
            title: document.getElementById('title').value,
            description: document.getElementById('description').value,
            is_completed: document.getElementById('is_completed').checked,
            timestamp: new Date().toISOString()
        };

        localStorage.setItem('todo_edit_{{ $todo->id }}', JSON.stringify(data));
    }

    // 자동 저장 데이터 복원
    function restoreAutoSave() {
        const saved = localStorage.getItem('todo_edit_{{ $todo->id }}');
        if (saved) {
            try {
                const data = JSON.parse(saved);
                const savedTime = new Date(data.timestamp);
                const now = new Date();

                // 1시간 이내의 자동저장 데이터만 복원
                if (now - savedTime < 60 * 60 * 1000) {
                    if (confirm('자동 저장된 데이터가 있습니다. 복원하시겠습니까?')) {
                        document.getElementById('title').value = data.title;
                        document.getElementById('description').value = data.description;
                        document.getElementById('is_completed').checked = data.is_completed;

                        updateStatus(document.getElementById('is_completed'));
                        checkChanges();
                    }
                }
            } catch (e) {
                console.error('자동저장 데이터 복원 실패:', e);
                localStorage.removeItem('todo_edit_{{ $todo->id }}');
            }
        }
    }

    // 이벤트 리스너 등록
    document.addEventListener('DOMContentLoaded', function() {
        // 자동 저장 데이터 복원
        restoreAutoSave();

        // 입력 필드 이벤트 리스너
        const titleInput = document.getElementById('title');
        const descriptionInput = document.getElementById('description');
        const completedInput = document.getElementById('is_completed');

        titleInput.addEventListener('input', function() {
            checkChanges();
            autoSave();
        });

        descriptionInput.addEventListener('input', function() {
            checkChanges();
            autoSave();
        });

        completedInput.addEventListener('change', function() {
            checkChanges();
            autoSave();
        });

        // 키보드 단축키
        document.addEventListener('keydown', function(e) {
            // Ctrl + Enter로 폼 제출
            if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
                e.preventDefault();
                document.querySelector('form').submit();
            }

            // Ctrl + R로 초기화
            if ((e.ctrlKey || e.metaKey) && e.key === 'r') {
                e.preventDefault();
                resetForm();
            }

            // Escape로 취소
            if (e.key === 'Escape') {
                if (hasChanges) {
                    if (confirm('변경사항이 저장되지 않았습니다. 정말 나가시겠습니까?')) {
                        window.location.href = '{{ route("todos.show", $todo) }}';
                    }
                } else {
                    window.location.href = '{{ route("todos.show", $todo) }}';
                }
            }
        });

        // 폼 제출시 자동저장 데이터 삭제
        document.querySelector('form').addEventListener('submit', function() {
            localStorage.removeItem('todo_edit_{{ $todo->id }}');
        });

        // 페이지 이탈시 확인
        window.addEventListener('beforeunload', function(e) {
            if (hasChanges) {
                e.preventDefault();
                e.returnValue = '변경사항이 저장되지 않았습니다.';
            }
        });

        // 초기 상태 확인
        checkChanges();
    });

    // 입력 필드 포커스 효과
    document.querySelectorAll('.form-control').forEach(function(input) {
        input.addEventListener('focus', function() {
            this.parentNode.style.transform = 'scale(1.02)';
            this.parentNode.style.transition = 'transform 0.2s ease';
        });

        input.addEventListener('blur', function() {
            this.parentNode.style.transform = 'scale(1)';
        });
    });

    // 진행률 표시 (선택사항)
    function updateProgress() {
        const title = document.getElementById('title').value;
        const description = document.getElementById('description').value;

        let progress = 0;
        if (title.trim()) progress += 50;
        if (description.trim()) progress += 50;

        // 진행률 바가 있다면 업데이트
        const progressBar = document.querySelector('.form-progress');
        if (progressBar) {
            progressBar.style.width = progress + '%';
        }
    }

    // 문자 수 카운터 (선택사항)
    function updateCharCount() {
        const title = document.getElementById('title');
        const titleCount = document.getElementById('title-count');

        if (titleCount) {
            const remaining = 255 - title.value.length;
            titleCount.textContent = remaining + '자 남음';
            titleCount.className = remaining < 20 ? 'text-danger' : 'text-muted';
        }
    }
</script>
@endsection
