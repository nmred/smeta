#include <stdio.h>
#include <limits.h>

int main(void)
{
	long test = 0L;
	
	printf("Enter an integer less than %ld", LONG_MAX);
	scanf("%ld", &test);
	
	if (test % 2 == 0L) {
		printf("The number %ld is even", test);	
		if ((test / 2L) % 2L == 0L) {
			printf("\nHalf of %ld is also even", test);
			printf("\nThat's interesting isn't it?\n");
		}
	} else {
		printf("The number %ld is odd\n", test);
	}

	return 0;
}
