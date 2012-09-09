#include <stdio.h>

int main(void)
{
	long sum = 0L;
	int count = 0;
	int i,j = 1;
	
	printf("\nEnter the number of integers you want to sum: ");
	scanf(" %d", &count);
	
	for (i = 1; i <= count; i++) {
		sum = 1L;
		j = 1;
		printf("\n1");
		while (j < i) {
			sum += ++j;	
			printf("+%d", j);
		}

		printf(" = %ld\n", sum);
	}	

	return 0;
}
